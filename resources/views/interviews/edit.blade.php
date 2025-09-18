@extends('layouts.main')
@section('title', 'Edit Interview')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Interview</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('interviews.index') }}">Interviews</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Interview Details</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('interviews.update', $interview) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $interview->name) }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $interview->email) }}">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $interview->phone) }}">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="qualification">Qualification</label>
                                        <input type="text" name="qualification" id="qualification" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification', $interview->qualification) }}">
                                        @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year">Year</label>
                                        <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $interview->year) }}">
                                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age', $interview->age) }}">
                                        @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="home_town">Home Town</label>
                                        <input type="text" name="home_town" id="home_town" class="form-control @error('home_town') is-invalid @enderror" value="{{ old('home_town', $interview->home_town) }}">
                                        @error('home_town')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="current_location">Current Location</label>
                                        <input type="text" name="current_location" id="current_location" class="form-control @error('current_location') is-invalid @enderror" value="{{ old('current_location', $interview->current_location) }}">
                                        @error('current_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_of_interview">Date of Interview <span class="text-danger">*</span></label>
                                        <input type="date" name="date_of_interview" id="date_of_interview" class="form-control @error('date_of_interview') is-invalid @enderror" value="{{ old('date_of_interview', $interview->date_of_interview ? $interview->date_of_interview->format('Y-m-d') : null) }}" required>
                                        @error('date_of_interview')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="interview_time">Interview Time</label>
                                        <input type="time" name="interview_time" id="interview_time" class="form-control @error('interview_time') is-invalid @enderror" value="{{ old('interview_time', $interview->interview_time) }}">
                                        @error('interview_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="position_applied">Position Applied</label>
                                        <input type="text" name="position_applied" id="position_applied" class="form-control @error('position_applied') is-invalid @enderror" value="{{ old('position_applied', $interview->position_applied) }}">
                                        @error('position_applied')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="interview_type">Interview Type <span class="text-danger">*</span></label>
                                        <select name="interview_type" id="interview_type" class="form-control @error('interview_type') is-invalid @enderror" required>
                                            <option value="">-- Select Type --</option>
                                            @foreach($interviewTypes as $type)
                                                <option value="{{ $type }}" {{ old('interview_type', $interview->interview_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                            @endforeach
                                        </select>
                                        @error('interview_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name_of_interviewer">Name of Interviewer</label>
                                        <input type="text" name="name_of_interviewer" id="name_of_interviewer" class="form-control @error('name_of_interviewer') is-invalid @enderror" value="{{ old('name_of_interviewer', $interview->name_of_interviewer) }}">
                                        @error('name_of_interviewer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="technical_interview_conducted_by">Technical Interview Conducted By</label>
                                        <input type="text" name="technical_interview_conducted_by" id="technical_interview_conducted_by" class="form-control @error('technical_interview_conducted_by') is-invalid @enderror" value="{{ old('technical_interview_conducted_by', $interview->technical_interview_conducted_by) }}">
                                        @error('technical_interview_conducted_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $interview->remarks) }}</textarea>
                                        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="technical_remarks">Technical Remarks</label>
                                        <textarea name="technical_remarks" id="technical_remarks" class="form-control @error('technical_remarks') is-invalid @enderror">{{ old('technical_remarks', $interview->technical_remarks) }}</textarea>
                                        @error('technical_remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="job_status">Job Status</label>
                                        <select name="job_status" id="job_status" class="form-control @error('job_status') is-invalid @enderror">
                                            <option value="">-- Select Status --</option>
                                            @foreach($jobStatuses as $status)
                                                <option value="{{ $status }}" {{ old('job_status', $interview->job_status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                        @error('job_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="marital_status">Marital Status</label>
                                        <input type="text" name="marital_status" id="marital_status" class="form-control @error('marital_status') is-invalid @enderror" value="{{ old('marital_status', $interview->marital_status) }}">
                                        @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="technical_skills">Technical Skills</label>
                                        <input type="text" name="technical_skills" id="technical_skills" class="form-control @error('technical_skills') is-invalid @enderror" value="{{ old('technical_skills', $interview->technical_skills) }}">
                                        @error('technical_skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="reference">Reference</label>
                                        <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $interview->reference) }}">
                                        @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_company_name">Last Company Name</label>
                                        <input type="text" name="last_company_name" id="last_company_name" class="form-control @error('last_company_name') is-invalid @enderror" value="{{ old('last_company_name', $interview->last_company_name) }}">
                                        @error('last_company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="employee_count">Employee Count</label>
                                        <input type="number" name="employee_count" id="employee_count" class="form-control @error('employee_count') is-invalid @enderror" value="{{ old('employee_count', $interview->employee_count) }}">
                                        @error('employee_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_job_position">Last Job Position</label>
                                        <input type="text" name="last_job_position" id="last_job_position" class="form-control @error('last_job_position') is-invalid @enderror" value="{{ old('last_job_position', $interview->last_job_position) }}">
                                        @error('last_job_position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_experience">Total Experience (years)</label>
                                        <input type="number" step="0.01" name="total_experience" id="total_experience" class="form-control @error('total_experience') is-invalid @enderror" value="{{ old('total_experience', $interview->total_experience) }}">
                                        @error('total_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="relevant_experience">Relevant Experience (years)</label>
                                        <input type="number" step="0.01" name="relevant_experience" id="relevant_experience" class="form-control @error('relevant_experience') is-invalid @enderror" value="{{ old('relevant_experience', $interview->relevant_experience) }}">
                                        @error('relevant_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_current_salary">Last/Current Salary</label>
                                        <input type="number" step="0.01" name="last_current_salary" id="last_current_salary" class="form-control @error('last_current_salary') is-invalid @enderror" value="{{ old('last_current_salary', $interview->last_current_salary) }}">
                                        @error('last_current_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="notice_period">Notice Period</label>
                                        <input type="text" name="notice_period" id="notice_period" class="form-control @error('notice_period') is-invalid @enderror" value="{{ old('notice_period', $interview->notice_period) }}">
                                        @error('notice_period')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="expected_salary">Expected Salary</label>
                                        <input type="number" step="0.01" name="expected_salary" id="expected_salary" class="form-control @error('expected_salary') is-invalid @enderror" value="{{ old('expected_salary', $interview->expected_salary) }}">
                                        @error('expected_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="negotiable">Negotiable</label>
                                        <select name="negotiable" id="negotiable" class="form-control @error('negotiable') is-invalid @enderror">
                                            <option value="0" {{ old('negotiable', $interview->negotiable) == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('negotiable', $interview->negotiable) == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('negotiable')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="immediate_joining">Immediate Joining</label>
                                        <select name="immediate_joining" id="immediate_joining" class="form-control @error('immediate_joining') is-invalid @enderror">
                                            <option value="0" {{ old('immediate_joining', $interview->immediate_joining) == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('immediate_joining', $interview->immediate_joining) == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('immediate_joining')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reason_for_leaving">Reason for Leaving</label>
                                        <textarea name="reason_for_leaving" id="reason_for_leaving" class="form-control @error('reason_for_leaving') is-invalid @enderror">{{ old('reason_for_leaving', $interview->reason_for_leaving) }}</textarea>
                                        @error('reason_for_leaving')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="other_benefits">Other Benefits</label>
                                        <input type="text" name="other_benefits" id="other_benefits" class="form-control @error('other_benefits') is-invalid @enderror" value="{{ old('other_benefits', $interview->other_benefits) }}">
                                        @error('other_benefits')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="communication_skills">Communication Skills</label>
                                        <input type="text" name="communication_skills" id="communication_skills" class="form-control @error('communication_skills') is-invalid @enderror" value="{{ old('communication_skills', $interview->communication_skills) }}">
                                        @error('communication_skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="health_condition">Health Condition</label>
                                        <input type="text" name="health_condition" id="health_condition" class="form-control @error('health_condition') is-invalid @enderror" value="{{ old('health_condition', $interview->health_condition) }}">
                                        @error('health_condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="currently_studying">Currently Studying</label>
                                        <select name="currently_studying" id="currently_studying" class="form-control @error('currently_studying') is-invalid @enderror">
                                            <option value="0" {{ old('currently_studying', $interview->currently_studying) == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('currently_studying', $interview->currently_studying) == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('currently_studying')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="interviewed_previously">Interviewed Previously</label>
                                        <select name="interviewed_previously" id="interviewed_previously" class="form-control @error('interviewed_previously') is-invalid @enderror">
                                            <option value="0" {{ old('interviewed_previously', $interview->interviewed_previously) == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('interviewed_previously', $interview->interviewed_previously) == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('interviewed_previously')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Update Interview</button>
                                <a href="{{ route('interviews.index') }}" class="btn btn-light btn-lg ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

