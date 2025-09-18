@extends('layouts.main')
@section('title', 'Interview Details')
@section('content')
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
                    <div class="card-header">
                        <h4>Interview Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">Name</dt>
                                    <dd class="col-sm-7">{{ $interview->name }}</dd>
                                    <dt class="col-sm-5">Email</dt>
                                    <dd class="col-sm-7">{{ $interview->email }}</dd>
                                    <dt class="col-sm-5">Phone</dt>
                                    <dd class="col-sm-7">{{ $interview->phone }}</dd>
                                    <dt class="col-sm-5">Qualification</dt>
                                    <dd class="col-sm-7">{{ $interview->qualification }}</dd>
                                    <dt class="col-sm-5">Year</dt>
                                    <dd class="col-sm-7">{{ $interview->year }}</dd>
                                    <dt class="col-sm-5">Age</dt>
                                    <dd class="col-sm-7">{{ $interview->age }}</dd>
                                    <dt class="col-sm-5">Home Town</dt>
                                    <dd class="col-sm-7">{{ $interview->home_town }}</dd>
                                    <dt class="col-sm-5">Current Location</dt>
                                    <dd class="col-sm-7">{{ $interview->current_location }}</dd>
                                    <dt class="col-sm-5">Date of Interview</dt>
                                    <dd class="col-sm-7">{{ $interview->date_of_interview ? $interview->date_of_interview->format('Y-m-d') : '' }}</dd>
                                    <dt class="col-sm-5">Interview Time</dt>
                                    <dd class="col-sm-7">{{ $interview->interview_time }}</dd>
                                    <dt class="col-sm-5">Position Applied</dt>
                                    <dd class="col-sm-7">{{ $interview->position_applied }}</dd>
                                    <dt class="col-sm-5">Interview Type</dt>
                                    <dd class="col-sm-7">{{ ucfirst($interview->interview_type) }}</dd>
                                    <dt class="col-sm-5">Name of Interviewer</dt>
                                    <dd class="col-sm-7">{{ $interview->name_of_interviewer }}</dd>
                                    <dt class="col-sm-5">Technical Interview Conducted By</dt>
                                    <dd class="col-sm-7">{{ $interview->technical_interview_conducted_by }}</dd>
                                    <dt class="col-sm-5">Job Status</dt>
                                    <dd class="col-sm-7">{{ $interview->job_status }}</dd>
                                    <dt class="col-sm-5">Marital Status</dt>
                                    <dd class="col-sm-7">{{ $interview->marital_status }}</dd>
                                    <dt class="col-sm-5">Technical Skills</dt>
                                    <dd class="col-sm-7">{{ $interview->technical_skills }}</dd>
                                    <dt class="col-sm-5">Reference</dt>
                                    <dd class="col-sm-7">{{ $interview->reference }}</dd>
                                    <dt class="col-sm-5">Last Company Name</dt>
                                    <dd class="col-sm-7">{{ $interview->last_company_name }}</dd>
                                    <dt class="col-sm-5">Employee Count</dt>
                                    <dd class="col-sm-7">{{ $interview->employee_count }}</dd>
                                    <dt class="col-sm-5">Last Job Position</dt>
                                    <dd class="col-sm-7">{{ $interview->last_job_position }}</dd>
                                    <dt class="col-sm-5">Total Experience</dt>
                                    <dd class="col-sm-7">{{ $interview->total_experience }}</dd>
                                    <dt class="col-sm-5">Relevant Experience</dt>
                                    <dd class="col-sm-7">{{ $interview->relevant_experience }}</dd>
                                    <dt class="col-sm-5">Last/Current Salary</dt>
                                    <dd class="col-sm-7">{{ $interview->last_current_salary }}</dd>
                                    <dt class="col-sm-5">Notice Period</dt>
                                    <dd class="col-sm-7">{{ $interview->notice_period }}</dd>
                                    <dt class="col-sm-5">Expected Salary</dt>
                                    <dd class="col-sm-7">{{ $interview->expected_salary }}</dd>
                                    <dt class="col-sm-5">Negotiable</dt>
                                    <dd class="col-sm-7">{{ $interview->negotiable ? 'Yes' : 'No' }}</dd>
                                    <dt class="col-sm-5">Immediate Joining</dt>
                                    <dd class="col-sm-7">{{ $interview->immediate_joining ? 'Yes' : 'No' }}</dd>
                                    <dt class="col-sm-5">Reason for Leaving</dt>
                                    <dd class="col-sm-7">{{ $interview->reason_for_leaving }}</dd>
                                    <dt class="col-sm-5">Other Benefits</dt>
                                    <dd class="col-sm-7">{{ $interview->other_benefits }}</dd>
                                    <dt class="col-sm-5">Communication Skills</dt>
                                    <dd class="col-sm-7">{{ $interview->communication_skills }}</dd>
                                    <dt class="col-sm-5">Health Condition</dt>
                                    <dd class="col-sm-7">{{ $interview->health_condition }}</dd>
                                    <dt class="col-sm-5">Currently Studying</dt>
                                    <dd class="col-sm-7">{{ $interview->currently_studying ? 'Yes' : 'No' }}</dd>
                                    <dt class="col-sm-5">Interviewed Previously</dt>
                                    <dd class="col-sm-7">{{ $interview->interviewed_previously ? 'Yes' : 'No' }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">Remarks</dt>
                                    <dd class="col-sm-7">{{ $interview->remarks }}</dd>
                                    <dt class="col-sm-5">Technical Remarks</dt>
                                    <dd class="col-sm-7">{{ $interview->technical_remarks }}</dd>
                                </dl>
                            </div>
                        </div>
                        <a href="{{ route('interviews.print', $interview) }}" target="_blank" class="btn btn-secondary mt-3"><i class="fas fa-print mr-1"></i> Print</a>
                        <a href="{{ route('interviews.index') }}" class="btn btn-light mt-3">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

