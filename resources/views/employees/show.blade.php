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
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                    <div class="card-body">
                        <div class="author-box-center">
                            <img alt="image" src="{{ asset('assets/img/users/user-1.png') }}" class="rounded-circle author-box-picture">
                            <div class="clearfix"></div>
                            <div class="author-box-name">
                                <a href="#">{{ $employee->name }}</a>
                            </div>
                            <div class="author-box-job">{{ optional($designations->where('id', $employee->jobInformation->designation_id ?? null)->first())->name ?? 'Employee' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="author-box-description">
                                <p>
                                    {{ $employee->empPersonalDetail->current_address ?? 'No address available' }}
                                </p>
                            </div>
                            <div class="mb-2 mt-3">
                                <div class="text-small font-weight-bold">Contact Information</div>
                            </div>
                            <div class="text-muted">
                                <p class="mb-1"><i class="fas fa-envelope mr-2"></i>{{ $employee->email }}</p>
                                <p class="mb-1"><i class="fas fa-phone mr-2"></i>{{ $employee->empPersonalDetail->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Personal Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="py-4">
                            <p class="clearfix">
                                <span class="float-left">
                                    Full Name
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->empPersonalDetail->first_name ?? '' }} {{ $employee->empPersonalDetail->last_name ?? '' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    Gender
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->empPersonalDetail->gender ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    Date of Birth
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->empPersonalDetail->date_of_birth ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    City
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->empPersonalDetail->city ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    State
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->empPersonalDetail->state ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    Country
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->empPersonalDetail->country ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Job Information</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled user-progress list-unstyled-border list-unstyled-noborder">
                            <li class="media">
                                <div class="media-body">
                                    <div class="media-title">Department</div>
                                </div>
                                <div class="media-progressbar p-t-10">
                                    <div class="progress" data-height="6">
                                        <div class="progress-bar bg-primary" data-width="100%"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-body">
                                    <div class="media-title">Designation</div>
                                </div>
                                <div class="media-progressbar p-t-10">
                                    <div class="progress" data-height="6">
                                        <div class="progress-bar bg-warning" data-width="100%"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-body">
                                    <div class="media-title">Work Type</div>
                                </div>
                                <div class="media-progressbar p-t-10">
                                    <div class="progress" data-height="6">
                                        <div class="progress-bar bg-green" data-width="100%"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-3">
                            <p class="clearfix">
                                <span class="float-left">
                                    <strong>Department:</strong>
                                </span>
                                <span class="float-right text-muted">
                                    {{ optional($departments->where('id', $employee->jobInformation->department_id ?? null)->first())->name ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    <strong>Designation:</strong>
                                </span>
                                <span class="float-right text-muted">
                                    {{ optional($designations->where('id', $employee->jobInformation->designation_id ?? null)->first())->name ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    <strong>Work Type:</strong>
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->jobInformation->work_type ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    <strong>Joining Date:</strong>
                                </span>
                                <span class="float-right text-muted">
                                    {{ $employee->jobInformation->joining_date ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                    <div class="padding-20">
                        <ul class="nav nav-tabs" id="myTab2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="about-tab2" data-toggle="tab" href="#about" role="tab"
                                  aria-selected="true">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab2" data-toggle="tab" href="#contact" role="tab"
                                  aria-selected="false">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="finance-tab2" data-toggle="tab" href="#finance" role="tab"
                                  aria-selected="false">Finance</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="documents-tab2" data-toggle="tab" href="#documents" role="tab"
                                  aria-selected="false">Documents</a>
                            </li>
                        </ul>
                        <div class="tab-content tab-bordered" id="myTab3Content">
                            <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab2">
                                <div class="row">
                                    <div class="col-md-3 col-6 b-r">
                                        <strong>Full Name</strong>
                                        <br>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->first_name ?? '' }} {{ $employee->empPersonalDetail->last_name ?? '' }}</p>
                                    </div>
                                    <div class="col-md-3 col-6 b-r">
                                        <strong>Email</strong>
                                        <br>
                                        <p class="text-muted">{{ $employee->email }}</p>
                                    </div>
                                    <div class="col-md-3 col-6 b-r">
                                        <strong>Phone</strong>
                                        <br>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->phone ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <strong>Gender</strong>
                                        <br>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->gender ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <strong>Current Address</strong>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->current_address ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Permanent Address</strong>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->permanent_address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <strong>City</strong>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->city ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>State</strong>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->state ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Country</strong>
                                        <p class="text-muted">{{ $employee->empPersonalDetail->country ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab2">
                                <div class="section-title">Emergency Contact</div>
                                @php $ec = $employee->emergencyContacts->first(); @endphp
                                @if($ec)
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Contact Name</strong>
                                        <p class="text-muted">{{ $ec->contact_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Relationship</strong>
                                        <p class="text-muted">{{ $ec->relationship ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Phone Number</strong>
                                        <p class="text-muted">{{ $ec->phone_number ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Alternate Phone</strong>
                                        <p class="text-muted">{{ $ec->alternate_phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @else
                                <p class="text-muted">No emergency contact information available.</p>
                                @endif
                                
                                <div class="section-title mt-4">Reporting Structure</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Reporting Manager</strong>
                                        <p class="text-muted">{{ optional($managers->where('id', $employee->jobInformation->reporting_manager_id ?? null)->first())->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Reporting Team Lead</strong>
                                        <p class="text-muted">{{ optional($teamLeads->where('id', $employee->jobInformation->reporting_teamlead_id ?? null)->first())->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Work Location</strong>
                                        <p class="text-muted">{{ $employee->jobInformation->work_location ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Probation End Date</strong>
                                        <p class="text-muted">{{ $employee->jobInformation->probation_end_date ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="finance" role="tabpanel" aria-labelledby="finance-tab2">
                                <div class="section-title">Finance Information</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Basic Salary</strong>
                                        <p class="text-muted">{{ $employee->empFinanceInformation->basic_salary ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Payment Type</strong>
                                        <p class="text-muted">{{ $employee->empFinanceInformation->payment_type ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="section-title mt-4">Bank Details</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Bank Name</strong>
                                        <p class="text-muted">{{ $employee->empFinanceInformation->bank_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Account Number</strong>
                                        <p class="text-muted">{{ $employee->empFinanceInformation->account_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab2">
                                <div class="section-title">Employee Documents</div>
                                @php $doc = $employee->empDocuments; @endphp
                                @if($doc)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="document-item">
                                            <strong>Resume</strong>
                                            @if($doc->resume)
                                                <a href="{{ asset($doc->resume) }}" target="_blank" class="btn btn-sm btn-primary ml-2">View</a>
                                            @else
                                                <span class="text-muted ml-2">Not uploaded</span>
                                            @endif
                                        </div>
                                        <div class="document-item mt-2">
                                            <strong>ID Proof</strong>
                                            @if($doc->id_proof)
                                                <a href="{{ asset($doc->id_proof) }}" target="_blank" class="btn btn-sm btn-primary ml-2">View</a>
                                            @else
                                                <span class="text-muted ml-2">Not uploaded</span>
                                            @endif
                                        </div>
                                        <div class="document-item mt-2">
                                            <strong>Address Proof</strong>
                                            @if($doc->address_proof)
                                                <a href="{{ asset($doc->address_proof) }}" target="_blank" class="btn btn-sm btn-primary ml-2">View</a>
                                            @else
                                                <span class="text-muted ml-2">Not uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="document-item">
                                            <strong>Offer Letter</strong>
                                            @if($doc->offer_letter)
                                                <a href="{{ asset($doc->offer_letter) }}" target="_blank" class="btn btn-sm btn-primary ml-2">View</a>
                                            @else
                                                <span class="text-muted ml-2">Not uploaded</span>
                                            @endif
                                        </div>
                                        <div class="document-item mt-2">
                                            <strong>Joining Letter</strong>
                                            @if($doc->joining_letter)
                                                <a href="{{ asset($doc->joining_letter) }}" target="_blank" class="btn btn-sm btn-primary ml-2">View</a>
                                            @else
                                                <span class="text-muted ml-2">Not uploaded</span>
                                            @endif
                                        </div>
                                        <div class="document-item mt-2">
                                            <strong>Education Documents</strong>
                                            @if($doc->education_documents)
                                                <a href="{{ asset($doc->education_documents) }}" target="_blank" class="btn btn-sm btn-primary ml-2">View</a>
                                            @else
                                                <span class="text-muted ml-2">Not uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @else
                                <p class="text-muted">No documents uploaded yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right mt-4">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">Edit Employee</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 