@php
    $formatDate = function ($d) {
        if (!$d) return '';
        try {
            return \Carbon\Carbon::parse($d)->format('d-M-Y');
        } catch (\Exception $e) {
            return (string) $d;
        }
    };

    $formatTime = function ($t) {
        if (!$t) return '';
        if ($t instanceof \DateTimeInterface) {
            try { return \Carbon\Carbon::instance($t)->format('H:i'); } catch (\Exception $e) {}
        }
        if (is_string($t)) {
            $raw = trim($t);
            foreach (['H:i', 'H:i:s', 'H:i:s.u'] as $fmt) {
                try { return \Carbon\Carbon::createFromFormat($fmt, $raw)->format('H:i'); } catch (\Exception $e) {}
            }
            return $raw;
        }
        try { return \Carbon\Carbon::parse($t)->format('H:i'); } catch (\Exception $e) { return (string)$t; }
    };
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HR Evaluation Form - {{ $interview->name }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#000; }
        .container { width: 800px; margin: 0 auto; }
        h1 { text-align: center; margin: 10px 0 20px; font-size: 22px; }
        .section-title { font-weight: bold; margin: 16px 0 8px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        .label { width: 25%; background: #f7f7f7; font-weight: bold; }
        .row-3 .label { width: 20%; }
        .no-border { border: 0; }
        .footer-line { margin-top: 30px; }
        .sign-line { border-bottom: 1px solid #000; width: 220px; height: 18px; display: inline-block; }
        @media print {
            @page { size: A4; margin: 14mm; }
            .no-print { display:none; }
        }
        .brand { font-weight: bold; color:#2c6db4; }
        .brand img { height: 60px; display:block; }
    </style>
</head>
<body onload="window.print()">
<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div class="brand">
            <img src="{{ asset('assets/img/eazisols.png') }}" alt="eazisols" onerror="this.outerHTML='eazisols'">
        </div>
        <div style="font-size:11px;">Date of Interview: <strong>{{ $formatDate($interview->date_of_interview) }}</strong></div>
    </div>
    <h1>HR Evaluation Form</h1>

    <div class="section-title">JOB SEEKER GENERAL INFORMATION:</div>
    <table>
        <tr>
            <td class="label">Name</td>
            <td>{{ $interview->name }}</td>
            <td class="label">Date of Interview</td>
            <td>{{ $formatDate($interview->date_of_interview) }}</td>
        </tr>
        <tr>
            <td class="label">Position Applied for</td>
            <td>{{ $interview->position_applied }}</td>
            <td class="label">Phone Number Called</td>
            <td>{{ $interview->phone }}</td>
        </tr>
        <tr>
            <td class="label">Qualification</td>
            <td>{{ $interview->qualification }}</td>
            <td class="label">Year</td>
            <td>{{ $interview->year }}</td>
        </tr>
        <tr>
            <td class="label">Home Town</td>
            <td>{{ $interview->home_town }}</td>
            <td class="label">Current Location</td>
            <td>{{ $interview->current_location }}</td>
        </tr>
        <tr>
            <td class="label">Age</td>
            <td>{{ $interview->age }}</td>
            <td class="label">Interview Time</td>
            <td>{{ $formatTime($interview->interview_time) }}</td>
        </tr>
        <tr>
            <td class="label">Interview Type</td>
            <td>{{ ucfirst($interview->interview_type) }}</td>
            <td class="label">Name Of Interviewer</td>
            <td>{{ $interview->name_of_interviewer }}</td>
        </tr>
        <tr>
            <td class="label">Communication Skills</td>
            <td>{{ $interview->communication_skills }}</td>
            <td class="label">Job Status</td>
            <td>{{ $interview->job_status }}</td>
        </tr>
        <tr>
            <td class="label">Health condition/Disability</td>
            <td>{{ $interview->health_condition }}</td>
            <td class="label">Technical Skills</td>
            <td>{{ $interview->technical_skills }}</td>
        </tr>
        <tr>
            <td class="label">Marital Status</td>
            <td>{{ $interview->marital_status }}</td>
            <td class="label">Reference</td>
            <td>{{ $interview->reference }}</td>
        </tr>
    </table>

    <div class="section-title">GENERAL SCREENING QUESTIONS:</div>
    <table>
        <tr>
            <td class="label">Last Company Name</td>
            <td>{{ $interview->last_company_name }}</td>
            <td class="label">Employee count</td>
            <td>{{ $interview->employee_count }}</td>
            <td class="label">Total Experience</td>
            <td>{{ $interview->total_experience }}</td>
        </tr>
        <tr>
            <td class="label">Last Job Position</td>
            <td>{{ $interview->last_job_position }}</td>
            <td class="label">Relevant Experience</td>
            <td colspan="3">{{ $interview->relevant_experience }}</td>
        </tr>
        <tr>
            <td class="label">Last Current Salary</td>
            <td>{{ $interview->last_current_salary }}</td>
            <td class="label">Notice Period</td>
            <td colspan="3">{{ $interview->notice_period }}</td>
        </tr>
        <tr>
            <td class="label">Expected Salary</td>
            <td>{{ $interview->expected_salary }}</td>
            <td class="label">Negotiable</td>
            <td>{{ $interview->negotiable ? 'Yes' : 'No' }}</td>
            <td class="label">Immediate joining</td>
            <td>{{ $interview->immediate_joining ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label">Reason for leaving</td>
            <td colspan="5">{{ $interview->reason_for_leaving }}</td>
        </tr>
        <tr>
            <td class="label">Other Benefits</td>
            <td colspan="5">{{ $interview->other_benefits }}</td>
        </tr>
    </table>

    <div class="section-title">Remarks:</div>
    <table>
        <tr>
            <td style="height:70px;">{{ $interview->remarks }}</td>
        </tr>
    </table>

    <table class="no-border" style="margin-top:8px;">
        <tr class="no-border">
            <td class="no-border">Are you currently Studying? <strong>{{ $interview->currently_studying ? 'Yes' : 'No' }}</strong></td>
            <td class="no-border">Have you been Interviewed Previously? <strong>{{ $interview->interviewed_previously ? 'Yes' : 'No' }}</strong></td>
        </tr>
    </table>

    <div class="section-title">Technical Remarks:</div>
    <table>
        <tr>
            <td style="height:70px;">{{ $interview->technical_remarks }}</td>
        </tr>
    </table>

    <div class="footer-line" style="margin-top:24px;">
        Technical Interview Conducted By:
        <div class="sign-line"></div>
        <div style="display:inline-block;margin-left:8px;">{{ $interview->technical_interview_conducted_by }}</div>
    </div>

    <div class="no-print" style="margin-top:16px;text-align:right;">
        <button onclick="window.print()">Print</button>
        <a href="{{ route('interviews.index') }}">Back</a>
    </div>
</div>
</body>
</html>


