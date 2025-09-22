@extends('layouts.main')
@section('title', 'Interview Details')
@section('content')
@php
    $dateStr = '';
    if ($interview->date_of_interview) {
        try { $dateStr = \Carbon\Carbon::parse($interview->date_of_interview)->format('M jS, Y'); } catch (\Exception $e) { $dateStr = (string)$interview->date_of_interview; }
    }
    $timeStr = '';
    if ($interview->interview_time) {
        foreach (['H:i', 'H:i:s', 'H:i:s.u'] as $fmt) {
            try { $timeStr = \Carbon\Carbon::createFromFormat($fmt, trim($interview->interview_time))->format('h:i A'); break; } catch (\Exception $e) {}
        }
        if ($timeStr === '') { try { $timeStr = \Carbon\Carbon::parse($interview->interview_time)->format('h:i A'); } catch (\Exception $e) { $timeStr = (string)$interview->interview_time; } }
    }
    $typeStr = ($interview->interview_type === 'onsite') ? 'On-site' : (($interview->interview_type === 'online') ? 'Online' : ucfirst($interview->interview_type ?? ''));

    $waPhone = preg_replace('/\D+/', '', $interview->phone ?? '');
    if ($waPhone) {
        if (strpos($waPhone, '0092') === 0) { $waPhone = '92' . substr($waPhone, 4); }
        elseif (strpos($waPhone, '92') === 0) { } 
        elseif (strpos($waPhone, '0') === 0) { $waPhone = '92' . substr($waPhone, 1); }
        elseif (strlen($waPhone) === 10 || strlen($waPhone) === 9) { $waPhone = '92' . $waPhone; }
    }
    $msg = "Dear {$interview->name},\n\nWe appreciate your interest in the position of {$interview->position_applied} at Eazisols. We would like to invite you for an " . strtolower($typeStr) . " interview on {$dateStr} at {$timeStr}.\n\nAddress: 65-J1, Wapda Town Phase 1, Lahore, Pakistan.\nLocation: https://goo.gl/maps/Naxu32J2NkDmjkKR8\n\nPlease confirm your availability for the interview by replying to this message.\n\nBest regards,\nHR Department\nEazisol";
    $waDesktop = $waPhone ? 'https://web.whatsapp.com/send?phone=' . $waPhone . '&text=' . urlencode($msg) : null;
    $waMobile = $waPhone ? 'https://api.whatsapp.com/send?phone=' . $waPhone . '&text=' . urlencode($msg) : null;
@endphp
<section class="section">
    <div class="section-header">
        <h1>Interview Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('interviews.index') }}">Interviews</a></div>
            <div class="breadcrumb-item">Details</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $interview->name }}</h4>
                            <small class="text-muted">{{ $interview->position_applied }} · {{ $typeStr }}</small>
                        </div>
                        <div class="card-header-action">
                            <a href="{{ route('interviews.print', $interview) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print mr-1"></i> Print</a>
                            @if($waPhone)
                                <a href="{{ $waDesktop }}" target="_blank" class="btn btn-success d-none d-md-inline-block"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
                                <a href="{{ $waMobile }}" target="_blank" class="btn btn-success d-inline-block d-md-none"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
                            @endif
                            <form action="{{ route('interviews.sendMail', $interview) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="subject" value="Interview Invitation - {{ $interview->position_applied }}">
                                <button type="submit" class="btn btn-info"><i class="fas fa-envelope mr-1"></i> Email</button>
                            </form>
                            <a href="{{ route('interviews.edit', $interview) }}" class="btn btn-primary"><i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                            <a href="{{ route('interviews.index') }}" class="btn btn-light">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-header"><h6 class="mb-0">Candidate</h6></div>
                                    <div class="card-body p-3">
                                        <div class="mb-2"><strong>Name:</strong> {{ $interview->name }}</div>
                                        <div class="mb-2"><strong>Email:</strong> {{ $interview->email }}</div>
                                        <div class="mb-2"><strong>Phone:</strong> {{ $interview->phone }}</div>
                                        <div class="mb-2"><strong>Qualification:</strong> {{ $interview->qualification }}</div>
                                        <div class="mb-2"><strong>Year:</strong> {{ $interview->year }}</div>
                                        <div class="mb-2"><strong>Age:</strong> {{ $interview->age }}</div>
                                        <div class="mb-2"><strong>Home Town:</strong> {{ $interview->home_town }}</div>
                                        <div class="mb-0"><strong>Current Location:</strong> {{ $interview->current_location }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-header"><h6 class="mb-0">Interview</h6></div>
                                    <div class="card-body p-3">
                                        <div class="mb-2"><strong>Date:</strong> {{ $dateStr }}</div>
                                        <div class="mb-2"><strong>Time:</strong> {{ $timeStr }}</div>
                                        <div class="mb-2"><strong>Position:</strong> {{ $interview->position_applied }}</div>
                                        <div class="mb-2"><strong>Type:</strong> <span class="badge badge-{{ $interview->interview_type === 'onsite' ? 'success' : 'info' }}">{{ $typeStr }}</span></div>
                                        <div class="mb-2"><strong>Interviewer:</strong> {{ $interview->name_of_interviewer }}</div>
                                        <div class="mb-0"><strong>Conducted By (Technical):</strong> {{ $interview->technical_interview_conducted_by }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card shadow-sm">
                                    <div class="card-header"><h6 class="mb-0">Job & Other</h6></div>
                                    <div class="card-body p-3">
                                        <div class="mb-2"><strong>Status:</strong> {{ $interview->job_status }}</div>
                                        <div class="mb-2"><strong>Marital Status:</strong> {{ $interview->marital_status }}</div>
                                        <div class="mb-2"><strong>Technical Skills:</strong> {{ $interview->technical_skills }}</div>
                                        <div class="mb-2"><strong>Reference:</strong> {{ $interview->reference }}</div>
                                        <div class="mb-2"><strong>Comm. Skills:</strong> {{ $interview->communication_skills }}</div>
                                        <div class="mb-2"><strong>Health:</strong> {{ $interview->health_condition }}</div>
                                        <div class="mb-0"><strong>Studying / Interviewed Before:</strong> {{ $interview->currently_studying ? 'Yes' : 'No' }} / {{ $interview->interviewed_previously ? 'Yes' : 'No' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <div class="card shadow-sm">
                                    <div class="card-header"><h6 class="mb-0">General Screening</h6></div>
                                    <div class="card-body p-3">
                                        <div class="mb-2"><strong>Last Company:</strong> {{ $interview->last_company_name }}</div>
                                        <div class="mb-2"><strong>Employee Count:</strong> {{ $interview->employee_count }}</div>
                                        <div class="mb-2"><strong>Total Experience:</strong> {{ $interview->total_experience }}</div>
                                        <div class="mb-2"><strong>Last Position:</strong> {{ $interview->last_job_position }}</div>
                                        <div class="mb-2"><strong>Relevant Experience:</strong> {{ $interview->relevant_experience }}</div>
                                        <div class="mb-2"><strong>Last/Current Salary:</strong> {{ $interview->last_current_salary }}</div>
                                        <div class="mb-2"><strong>Notice Period:</strong> {{ $interview->notice_period }}</div>
                                        <div class="mb-2"><strong>Expected Salary:</strong> {{ $interview->expected_salary }}</div>
                                        <div class="mb-0"><strong>Negotiable / Immediate Joining:</strong> {{ $interview->negotiable ? 'Yes' : 'No' }} / {{ $interview->immediate_joining ? 'Yes' : 'No' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header"><h6 class="mb-0">Remarks</h6></div>
                                    <div class="card-body p-3">
                                        <div class="mb-3"><strong>Remarks:</strong><div class="border rounded p-2 mt-1" style="min-height:80px;">{!! nl2br(e($interview->remarks)) !!}</div></div>
                                        <div class="mb-3"><strong>Technical Remarks:</strong><div class="border rounded p-2 mt-1" style="min-height:80px;">{!! nl2br(e($interview->technical_remarks)) !!}</div></div>
                                        <div class="mb-3"><strong>Reason for Leaving:</strong><div class="border rounded p-2 mt-1" style="min-height:60px;">{!! nl2br(e($interview->reason_for_leaving)) !!}</div></div>
                                        <div class="mb-0"><strong>Other Benefits:</strong><div class="border rounded p-2 mt-1" style="min-height:40px;">{!! nl2br(e($interview->other_benefits)) !!}</div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

