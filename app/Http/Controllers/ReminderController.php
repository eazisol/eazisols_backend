<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReminderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Check if user has permission to manage reminders
            if (!auth()->user()->hasPermission('dash_reminders')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the reminders.
     */
    public function index()
    {
        $reminders = Auth::user()->reminders()
            ->orderBy('day_of_month')
            ->orderBy('time_of_day')
            ->paginate(10);

        return view('reminders.index', compact('reminders'));
    }

    /**
     * Show the form for creating a new reminder.
     */
    public function create()
    {
        return view('reminders.create');
    }

    /**
     * Store a newly created reminder in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'day_of_month' => 'required|integer|min:1|max:31',
            'time_of_day' => 'required|date_format:H:i',
        ]);

        $reminder = new Reminder([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'day_of_month' => $request->day_of_month,
            'time_of_day' => $request->time_of_day . ':00',
        ]);

        // Calculate the next trigger date
        $reminder->next_trigger_at = $reminder->calculateNextTrigger();
        $reminder->save();

        return redirect()->route('reminders.index')
            ->with('success', 'Reminder created successfully!');
    }

    /**
     * Display the specified reminder.
     */
    public function show(Reminder $reminder)
    {
        // Ensure user can only view their own reminders
        if ($reminder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('reminders.show', compact('reminder'));
    }

    /**
     * Show the form for editing the specified reminder.
     */
    public function edit(Reminder $reminder)
    {
        // Ensure user can only edit their own reminders
        if ($reminder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('reminders.edit', compact('reminder'));
    }

    /**
     * Update the specified reminder in storage.
     */
    public function update(Request $request, Reminder $reminder)
    {
        // Ensure user can only update their own reminders
        if ($reminder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'day_of_month' => 'required|integer|min:1|max:31',
            'time_of_day' => 'required|date_format:H:i',
        ]);

        $reminder->update([
            'title' => $request->title,
            'description' => $request->description,
            'day_of_month' => $request->day_of_month,
            'time_of_day' => $request->time_of_day . ':00',
        ]);

        // Recalculate next trigger date
        $reminder->next_trigger_at = $reminder->calculateNextTrigger();
        $reminder->notified = false; // Reset notification status
        $reminder->save();

        return redirect()->route('reminders.index')
            ->with('success', 'Reminder updated successfully!');
    }

    /**
     * Toggle the active status of the reminder.
     */
    public function toggleStatus(Reminder $reminder)
    {
        // Ensure user can only toggle their own reminders
        if ($reminder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $reminder->is_active = !$reminder->is_active;
        $reminder->save();

        $status = $reminder->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('reminders.index')
            ->with('success', "Reminder {$status} successfully!");
    }

    /**
     * Remove the specified reminder from storage.
     */
    public function destroy(Reminder $reminder)
    {
        // Ensure user can only delete their own reminders
        if ($reminder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $reminder->delete();

        return redirect()->route('reminders.index')
            ->with('success', 'Reminder deleted successfully!');
    }

    /**
     * Test a reminder by sending it immediately.
     */
    public function test(Reminder $reminder)
    {
        // Ensure user can only test their own reminders
        if ($reminder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Send test email
            \App\Helpers\MailHelper::send(
                $reminder->user->email,
                $reminder->user->name,
                '[TEST] ' . $reminder->title,
                'emails.reminder',
                [
                    'reminder' => $reminder,
                    'user' => $reminder->user,
                    'isTest' => true
                ]
            );

            return redirect()->route('reminders.index')
                ->with('success', 'Test reminder sent successfully!');
        } catch (\Exception $e) {
            return redirect()->route('reminders.index')
                ->with('error', 'Failed to send test reminder: ' . $e->getMessage());
        }
    }
}