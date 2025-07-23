@extends('layouts.main')

@section('title')
    Employee Details
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Employee Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></div>
            <div class="breadcrumb-item">Employee Details</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Employee Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>User Details</h5>
                                <p><strong>Name:</strong> {{ $employee->name }}</p>
                                <p><strong>Email:</strong> {{ $employee->email }}</p>
                                <p><strong>Role:</strong> {{ $employee->role->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Personal Details</h5>
                                <p><strong>First Name:</strong> {{ $employee->empPersonalDetail->first_name ?? '' }}</p>
                                <p><strong>Last Name:</strong> {{ $employee->empPersonalDetail->last_name ?? '' }}</p>
                                <p><strong>Phone:</strong> {{ $employee->empPersonalDetail->phone ?? '' }}</p>
                                <p><strong>Gender:</strong> {{ $employee->empPersonalDetail->gender ?? '' }}</p>
                                <p><strong>Date of Birth:</strong> {{ $employee->empPersonalDetail->date_of_birth ?? '' }}</p>
                                <p><strong>Current Address:</strong> {{ $employee->empPersonalDetail->current_address ?? '' }}</p>
                                <p><strong>Permanent Address:</strong> {{ $employee->empPersonalDetail->permanent_address ?? '' }}</p>
                                <p><strong>City:</strong> {{ $employee->empPersonalDetail->city ?? '' }}</p>
                                <p><strong>State:</strong> {{ $employee->empPersonalDetail->state ?? '' }}</p>
                                <p><strong>Country:</strong> {{ $employee->empPersonalDetail->country ?? '' }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Emergency Contact</h5>
                                @php $ec = $employee->emergencyContacts->first(); @endphp
                                <p><strong>Contact Name:</strong> {{ $ec->contact_name ?? '' }}</p>
                                <p><strong>Relationship:</strong> {{ $ec->relationship ?? '' }}</p>
                                <p><strong>Phone Number:</strong> {{ $ec->phone_number ?? '' }}</p>
                                <p><strong>Alternate Phone:</strong> {{ $ec->alternate_phone ?? '' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Job Information</h5>
                                <p><strong>Department:</strong> {{ optional($departments->where('id', $employee->jobInformation->department_id ?? null)->first())->name ?? '' }}</p>
                                <p><strong>Designation:</strong> {{ optional($designations->where('id', $employee->jobInformation->designation_id ?? null)->first())->name ?? '' }}</p>
                                <p><strong>Work Type:</strong> {{ $employee->jobInformation->work_type ?? '' }}</p>
                                <p><strong>Joining Date:</strong> {{ $employee->jobInformation->joining_date ?? '' }}</p>
                                <p><strong>Probation End Date:</strong> {{ $employee->jobInformation->probation_end_date ?? '' }}</p>
                                <p><strong>Reporting Manager:</strong> {{ optional($managers->where('id', $employee->jobInformation->reporting_manager_id ?? null)->first())->name ?? '' }}</p>
                                <p><strong>Reporting Team Lead:</strong> {{ optional($teamLeads->where('id', $employee->jobInformation->reporting_teamlead_id ?? null)->first())->name ?? '' }}</p>
                                <p><strong>Work Location:</strong> {{ $employee->jobInformation->work_location ?? '' }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Finance Information</h5>
                                <p><strong>Basic Salary:</strong> {{ $employee->empFinanceInformation->basic_salary ?? '' }}</p>
                                <p><strong>Bank Name:</strong> {{ $employee->empFinanceInformation->bank_name ?? '' }}</p>
                                <p><strong>Account Number:</strong> {{ $employee->empFinanceInformation->account_number ?? '' }}</p>
                                <p><strong>Payment Type:</strong> {{ $employee->empFinanceInformation->payment_type ?? '' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Documents</h5>
                                @php $doc = $employee->empDocuments; @endphp
                                <ul>
                                    <li><strong>Resume:</strong> @if($doc && $doc->resume) <a href="{{ asset($doc->resume) }}" target="_blank">View</a> @endif</li>
                                    <li><strong>ID Proof:</strong> @if($doc && $doc->id_proof) <a href="{{ asset($doc->id_proof) }}" target="_blank">View</a> @endif</li>
                                    <li><strong>Address Proof:</strong> @if($doc && $doc->address_proof) <a href="{{ asset($doc->address_proof) }}" target="_blank">View</a> @endif</li>
                                    <li><strong>Offer Letter:</strong> @if($doc && $doc->offer_letter) <a href="{{ asset($doc->offer_letter) }}" target="_blank">View</a> @endif</li>
                                    <li><strong>Joining Letter:</strong> @if($doc && $doc->joining_letter) <a href="{{ asset($doc->joining_letter) }}" target="_blank">View</a> @endif</li>
                                    <li><strong>Contract Letter:</strong> @if($doc && $doc->contract_letter) <a href="{{ asset($doc->contract_letter) }}" target="_blank">View</a> @endif</li>
                                    <li><strong>Education Documents:</strong> @if($doc && $doc->education_documents) <a href="{{ asset($doc->education_documents) }}" target="_blank">View</a> @endif</li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer text-right mt-4">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
                            {{-- <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-info">Edit</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 