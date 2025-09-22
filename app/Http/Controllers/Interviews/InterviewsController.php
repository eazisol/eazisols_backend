<?php

namespace App\Http\Controllers\Interviews;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Interview;
use App\Helpers\MailHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class InterviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Interview::query();

        // Search functionality (by name, email, phone, position_applied)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('position_applied', 'LIKE', "%{$search}%");
            });
        }

        // Filter by interview_type
        if ($request->has('interview_type') && $request->interview_type != '') {
            $query->where('interview_type', $request->interview_type);
        }

        // Filter by job_status
        if ($request->has('job_status') && $request->job_status != '') {
            $query->where('job_status', $request->job_status);
        }

        // Sort options
        $sort = $request->sort ?? 'date_of_interview';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $interviews = $query->paginate(10);
        $interviewTypes = ['onsite', 'online'];
        $jobStatuses = ['offered', 'rejected', 'on-hold'];

        return view('interviews.index', compact('interviews', 'interviewTypes', 'jobStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $interviewTypes = ['onsite', 'online'];
        $jobStatuses = ['offered', 'rejected', 'on-hold'];
        return view('interviews.create', compact('interviewTypes', 'jobStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'age' => 'nullable|integer',
            'home_town' => 'nullable|string|max:255',
            'current_location' => 'nullable|string|max:255',
            'date_of_interview' => 'required|date',
            'interview_time' => 'nullable',
            'position_applied' => 'nullable|string|max:255',
            'interview_type' => 'required|in:onsite,online',
            'name_of_interviewer' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'technical_remarks' => 'nullable|string',
            'technical_interview_conducted_by' => 'nullable|string|max:255',
            'job_status' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'technical_skills' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'last_company_name' => 'nullable|string|max:255',
            'employee_count' => 'nullable|integer',
            'total_experience' => 'nullable|numeric',
            'last_job_position' => 'nullable|string|max:255',
            'relevant_experience' => 'nullable|numeric',
            'last_current_salary' => 'nullable|numeric',
            'notice_period' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|numeric',
            'negotiable' => 'nullable|boolean',
            'immediate_joining' => 'nullable|boolean',
            'reason_for_leaving' => 'nullable|string',
            'other_benefits' => 'nullable|string|max:255',
            'communication_skills' => 'nullable|string|max:255',
            'health_condition' => 'nullable|string|max:255',
            'currently_studying' => 'nullable|boolean',
            'interviewed_previously' => 'nullable|boolean',
        ]);
        Interview::create($validated);
        return redirect()->route('interviews.index')->with('success', 'Interview created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Interview $interview)
    {
        return view('interviews.show', compact('interview'));
    }

    /**
     * Print the specified resource as HR Evaluation form.
     *
     * @param  Interview  $interview
     * @return \Illuminate\Http\Response
     */
    public function print(Interview $interview)
    {
        return view('interviews.print', compact('interview'));
    }

    /**
     * Send an email to interviewee using existing MailHelper configuration.
     */
    public function sendMail(Interview $interview, Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        $to = $interview->email;
        if (!$to) {
            return back()->with('error', 'No email found for this interview.');
        }

        $subject = $request->input('subject') ?: 'Regarding your interview at Eazisols';
        $data = [
            'interview' => $interview,
            'messageBody' => $request->input('message') ?: 'Thank you for your time. We will get back to you soon.',
        ];

        try {
            MailHelper::send($to, $interview->name ?? '', $subject, 'emails.interview-generic', $data);
            return back()->with('success', 'Email sent successfully.');
        } catch (\Exception $e) {
            Log::error('Interview email send failed', ['error' => $e->getMessage(), 'interview_id' => $interview->id]);
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Send a Slack message to a channel via Incoming Webhook.
     */
    public function sendSlack(Interview $interview, Request $request)
    {
        $webhookUrl = env('SLACK_WEBHOOK_URL');
        if (!$webhookUrl) {
            return back()->with('error', 'Slack webhook URL is not configured. Set SLACK_WEBHOOK_URL in .env');
        }

        $date = '';
        if ($interview->date_of_interview) {
            try { $date = \Carbon\Carbon::parse($interview->date_of_interview)->format('M jS, Y'); } catch (\Exception $e) { $date = (string)$interview->date_of_interview; }
        }
        $time = '';
        if ($interview->interview_time) {
            foreach (['H:i', 'H:i:s', 'H:i:s.u'] as $fmt) {
                try { $time = \Carbon\Carbon::createFromFormat($fmt, trim($interview->interview_time))->format('h:i A'); break; } catch (\Exception $e) {}
            }
            if ($time === '') { try { $time = \Carbon\Carbon::parse($interview->interview_time)->format('h:i A'); } catch (\Exception $e) { $time = (string)$interview->interview_time; } }
        }
        $type = ($interview->interview_type === 'onsite') ? 'on-site' : (($interview->interview_type === 'online') ? 'online' : ucfirst($interview->interview_type ?? ''));

        $text = "Interview Reminder\n" .
            "Candidate: {$interview->name}\n" .
            "Position: {$interview->position_applied}\n" .
            "Type: {$type}\n" .
            "Date: {$date}\n" .
            "Time: {$time}\n" .
            "Address: 65-J1, Wapda Town Phase 1, Lahore, Pakistan.\n" .
            "Maps: https://goo.gl/maps/Naxu32J2NkDmjkKR8";

        try {
            $payload = [
                'text' => $text,
            ];
            $resp = Http::asJson()->post($webhookUrl, $payload);
            if ($resp->successful()) {
                return back()->with('success', 'Slack notification sent.');
            }
            Log::error('Slack webhook failed', ['status' => $resp->status(), 'body' => $resp->body()]);
            return back()->with('error', 'Failed to send Slack notification.');
        } catch (\Throwable $e) {
            Log::error('Slack webhook exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to send Slack notification: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Interview $interview)
    {
        $interviewTypes = ['onsite', 'online'];
        $jobStatuses = ['offered', 'rejected', 'on-hold'];
        return view('interviews.edit', compact('interview', 'interviewTypes', 'jobStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'age' => 'nullable|integer',
            'home_town' => 'nullable|string|max:255',
            'current_location' => 'nullable|string|max:255',
            'date_of_interview' => 'required|date',
            'interview_time' => 'nullable',
            'position_applied' => 'nullable|string|max:255',
            'interview_type' => 'required|in:onsite,online',
            'name_of_interviewer' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'technical_remarks' => 'nullable|string',
            'technical_interview_conducted_by' => 'nullable|string|max:255',
            'job_status' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'technical_skills' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'last_company_name' => 'nullable|string|max:255',
            'employee_count' => 'nullable|integer',
            'total_experience' => 'nullable|numeric',
            'last_job_position' => 'nullable|string|max:255',
            'relevant_experience' => 'nullable|numeric',
            'last_current_salary' => 'nullable|numeric',
            'notice_period' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|numeric',
            'negotiable' => 'nullable|boolean',
            'immediate_joining' => 'nullable|boolean',
            'reason_for_leaving' => 'nullable|string',
            'other_benefits' => 'nullable|string|max:255',
            'communication_skills' => 'nullable|string|max:255',
            'health_condition' => 'nullable|string|max:255',
            'currently_studying' => 'nullable|boolean',
            'interviewed_previously' => 'nullable|boolean',
        ]);
        $interview->update($validated);
        return redirect()->route('interviews.index')->with('success', 'Interview updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Interview $interview)
    {
        $interview->delete();
        return redirect()->route('interviews.index')->with('success', 'Interview deleted successfully.');
    }
}
