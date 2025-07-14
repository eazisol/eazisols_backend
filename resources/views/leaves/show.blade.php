@extends('layouts.main')

@section('title')
    Leave Request Details
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Leave Request Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Requests</a></div>
            <div class="breadcrumb-item">Details</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>×</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>×</span></button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Leave Request Information</h4>
                        <div class="card-header-action">
                            @switch($leave->status)
                                @case('pending')
                                    <span class="badge badge-warning">Pending</span>
                                    @break
                                @case('approved')
                                    <span class="badge badge-success">Approved</span>
                                    @break
                                @case('rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            @if(Auth::id() !== $leave->user_id)
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Employee</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{ $leave->user ? $leave->user->name : 'N/A' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Start Date</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $leave->start_date ? $leave->start_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">End Date</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $leave->end_date ? $leave->end_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Duration</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ ($leave->start_date && $leave->end_date) ? $leave->duration . ' day(s)' : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Reason</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $leave->reason }}</p>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Applied On</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $leave->created_at ? $leave->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            @if($leave->created_at && $leave->updated_at && $leave->created_at->ne($leave->updated_at))
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Last Updated</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{ $leave->updated_at ? $leave->updated_at->format('d M Y, h:i A') : 'N/A' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Actions</h4>
                    </div>
                    <div class="card-body">
                        @if($leave->status === 'pending')
                            @if(Auth::id() === 1) {{-- Admin user --}}
                                <div class="buttons mb-3">
                                    <form action="{{ route('leaves.update-status', $leave) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-check-circle"></i> Approve Leave
                                        </button>
                                    </form>
                                </div>
                                <div class="buttons mb-3">
                                    <form action="{{ route('leaves.update-status', $leave) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-times-circle"></i> Reject Leave
                                        </button>
                                    </form>
                                </div>
                            @endif
                            
                            {{-- @if(Auth::id() === $leave->user_id || Auth::id() === 1)
                                <div class="buttons mb-3">
                                    <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-edit"></i> Edit Request
                                    </a>
                                </div>
                                <div class="buttons">
                                    <form action="{{ route('leaves.destroy', $leave) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this leave request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-trash"></i> Delete Request
                                        </button>
                                    </form>
                                </div>
                            @endif --}}
                        @endif
                        
                        <div class="buttons mt-3">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 