@extends('layouts.main')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Application Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('applied-jobs.index') }}">Job Applications</a></div>
            <div class="breadcrumb-item">Application Details</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="card">
                    <div class="card-header">
                        <h4>Application #{{ $appliedJob->id }}</h4>
                        <div class="card-header-action">
                            <span class="badge badge-{{ 
                                $appliedJob->status == 'pending' ? 'warning' : 
                                ($appliedJob->status == 'viewed' ? 'info' : 
                                ($appliedJob->status == 'approved' ? 'success' : 'danger')) 
                            }}">
                                {{ ucfirst($appliedJob->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="section-title">Personal Information</div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $appliedJob->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $appliedJob->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{ $appliedJob->phone ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Resume</th>
                                            <td>
                                                <a href="{{ asset($appliedJob->resume) }}" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-download"></i> Download Resume
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Current Company</th>
                                            <td>{{ $appliedJob->current_company ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Current Position</th>
                                            <td>{{ $appliedJob->current_position ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Experience</th>
                                            <td>{{ $appliedJob->experience_years }} {{ Str::plural('year', $appliedJob->experience_years) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Expected Salary</th>
                                            <td>{{ $appliedJob->expected_salary ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Available From</th>
                                            <td>{{ $appliedJob->available_from ? $appliedJob->available_from->format('M d, Y') : 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="section-title">Application Details</div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Position Applied</th>
                                            <td>
                                                <a href="{{ route('careers.show', $appliedJob->career) }}" target="_blank">
                                                    {{ $appliedJob->career->title }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Applied On</th>
                                            <td>{{ $appliedJob->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Reviewed On</th>
                                            <td>{{ $appliedJob->reviewed_at ? $appliedJob->reviewed_at->format('M d, Y H:i') : 'Not reviewed yet' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Portfolio URL</th>
                                            <td>
                                                @if($appliedJob->portfolio_url)
                                                    <a href="{{ $appliedJob->portfolio_url }}" target="_blank">{{ $appliedJob->portfolio_url }}</a>
                                                @else
                                                    Not provided
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>LinkedIn</th>
                                            <td>
                                                @if($appliedJob->linkedin_url)
                                                    <a href="{{ $appliedJob->linkedin_url }}" target="_blank">{{ $appliedJob->linkedin_url }}</a>
                                                @else
                                                    Not provided
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>GitHub</th>
                                            <td>
                                                @if($appliedJob->github_url)
                                                    <a href="{{ $appliedJob->github_url }}" target="_blank">{{ $appliedJob->github_url }}</a>
                                                @else
                                                    Not provided
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="section-title">Skills</div>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $appliedJob->skills ?? 'No skills provided' }}
                                    </div>
                                </div>
                                
                                <div class="section-title">Education</div>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $appliedJob->education ?? 'No education details provided' }}
                                    </div>
                                </div>
                                
                                <div class="section-title">Certifications</div>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $appliedJob->certifications ?? 'No certifications provided' }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="section-title">Cover Letter</div>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $appliedJob->cover_letter ?? 'No cover letter provided' }}
                                    </div>
                                </div>
                                
                                <div class="section-title">Additional Information</div>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $appliedJob->additional_info ?? 'No additional information provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="section-title">Admin Notes & Status Update</div>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('applied-jobs.update-status', $appliedJob) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT">
                                            
                                            <div class="form-group">
                                                <label>Application Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="pending" {{ $appliedJob->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="viewed" {{ $appliedJob->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                                    <option value="approved" {{ $appliedJob->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="rejected" {{ $appliedJob->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Admin Notes</label>
                                                <textarea name="admin_notes" class="form-control" rows="5">{{ $appliedJob->admin_notes }}</textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                                <a href="{{ route('applied-jobs.index') }}" class="btn btn-light">Back to List</a>
                                            </div>
                                        </form>
                                        
                                        {{-- <form action="{{ route('applied-jobs.destroy', $appliedJob) }}" method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger float-right">Delete Application</button>
                                        </form> --}}
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