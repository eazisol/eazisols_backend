<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    /**
     * Display a listing of leave requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if user is admin
        $isAdmin = Auth::user()->id === 1; // This is simplistic; use proper role-based auth in production
        
        $status = $request->query('status', 'all');
        $query = Leave::with('user')->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Regular users only see their own leave requests
        if (!$isAdmin) {
            $query->where('user_id', Auth::id());
        }
        
        // For quick access to counts
        $counts = [
            'pending' => Leave::pending()->count(),
            'approved' => Leave::approved()->count(),
            'rejected' => Leave::rejected()->count(),
        ];
        
        $leaves = $query->paginate(10);
        
        return view('leaves.index', compact('leaves', 'counts', 'status', 'isAdmin'));
    }
    
    /**
     * Show the form for creating a new leave request.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leaves.create');
    }
    
    /**
     * Store a newly created leave request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->route('leaves.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check for existing leave requests in the same period
        $existingLeave = Leave::where('user_id', Auth::id())
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->first();
            
        if ($existingLeave) {
            return redirect()
                ->route('leaves.create')
                ->with('error', 'You already have a leave request for this period.');
        }
        
        $leave = new Leave([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => Leave::STATUS_PENDING,
        ]);
        
        $leave->save();
        
        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }
    
    /**
     * Display the specified leave request.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leave = Leave::with('user')->find($id);

        if (!$leave) {
            abort(404);
        }
        // Regular users can only view their own leave requests
        if (Auth::id() !== $leave->user_id && Auth::id() !== 1) {
            abort(403);
        }
        
        return view('leaves.show', compact('leave'));
    }
    
    /**
     * Show the form for editing the specified leave request.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        // Only allow editing pending leave requests
        if ($leave->status !== Leave::STATUS_PENDING) {
            return redirect()
                ->route('leaves.show', $leave)
                ->with('error', 'You can only edit pending leave requests.');
        }
        
        // Regular users can only edit their own leave requests
        if (Auth::id() !== $leave->user_id && Auth::id() !== 1) {
            abort(403);
        }
        
        return view('leaves.edit', compact('leave'));
    }
    
    /**
     * Update the specified leave request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leave $leave)
    {
        // Only allow updating pending leave requests
        if ($leave->status !== Leave::STATUS_PENDING) {
            return redirect()
                ->route('leaves.show', $leave)
                ->with('error', 'You can only update pending leave requests.');
        }
        
        // Regular users can only update their own leave requests
        if (Auth::id() !== $leave->user_id && Auth::id() !== 1) {
            abort(403);
        }
        
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->route('leaves.edit', $leave)
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check for existing leave requests in the same period (excluding current)
        $existingLeave = Leave::where('user_id', $leave->user_id)
            ->where('id', '!=', $leave->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->first();
            
        if ($existingLeave) {
            return redirect()
                ->route('leaves.edit', $leave)
                ->with('error', 'There is already a leave request for this period.');
        }
        
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->reason = $request->reason;
        $leave->save();
        
        return redirect()
            ->route('leaves.show', $leave)
            ->with('success', 'Leave request updated successfully.');
    }
    
    /**
     * Update the status of a leave request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Leave $leave)
    {
        // Only admin can update status
        if (Auth::id() !== 1) { // This is simplistic; use proper role-based auth in production
            abort(403);
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . Leave::STATUS_APPROVED . ',' . Leave::STATUS_REJECTED,
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $leave->status = $request->status;
        $leave->save();
        
        // If approved, update attendance records for the leave period
        if ($request->status === Leave::STATUS_APPROVED) {
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Skip weekends
                if (!$currentDate->isWeekend()) {
                    $attendance = Attendance::firstOrNew([
                        'user_id' => $leave->user_id,
                        'date' => $currentDate->format('Y-m-d'),
                    ]);
                    
                    $attendance->status = Attendance::STATUS_LEAVE;
                    $attendance->save();
                }
                
                $currentDate->addDay();
            }
        }
        
        return redirect()
            ->back()
            ->with('success', 'Leave request ' . ($request->status === Leave::STATUS_APPROVED ? 'approved' : 'rejected') . ' successfully.');
    }
    
    /**
     * Remove the specified leave request.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leave)
    {
        // Only allow deleting pending leave requests
        if ($leave->status !== Leave::STATUS_PENDING) {
            return redirect()
                ->route('leaves.index')
                ->with('error', 'You can only delete pending leave requests.');
        }
        
        // Regular users can only delete their own leave requests
        if (Auth::id() !== $leave->user_id && Auth::id() !== 1) {
            abort(403);
        }
        
        $leave->delete();
        
        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }
    
    /**
     * Display the calendar view of leaves.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function calendar(Request $request)
    {
        // Default to current month if no date is provided
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);
        
        // Get the first and last day of the month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Get all users
        $users = User::all();
        
        // Get all approved leaves for the month
        $leaves = Leave::where('status', Leave::STATUS_APPROVED)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->with('user')
            ->get();
        
        // Create calendar data
        $calendar = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $calendar[$currentDate->format('Y-m-d')] = [
                'date' => $currentDate->copy(),
                'isWeekend' => $currentDate->isWeekend(),
                'leaves' => collect(),
            ];
            $currentDate->addDay();
        }
        
        // Map leaves to calendar days
        foreach ($leaves as $leave) {
            $leaveStart = max($leave->start_date, $startDate);
            $leaveEnd = min($leave->end_date, $endDate);
            $currentDate = Carbon::parse($leaveStart);
            
            while ($currentDate->lte($leaveEnd)) {
                $dateKey = $currentDate->format('Y-m-d');
                if (isset($calendar[$dateKey])) {
                    $calendar[$dateKey]['leaves']->push($leave);
                }
                $currentDate->addDay();
            }
        }
        
        return view('leaves.calendar', compact('users', 'leaves', 'calendar', 'month', 'year'));
    }

    /**
     * Display the leave history for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $leaves = $user->leaves()
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('leaves.history', compact('leaves'));
    }
}