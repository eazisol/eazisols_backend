@extends('layouts.main')

@section('title', 'Query Details')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Query Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries</a></div>
            <div class="breadcrumb-item">Query #{{ $query->id }}</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Query Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Query Type:</strong>
                            @if($query->isContact())
                                <span class="badge badge-primary">Contact</span>
                            @else
                                <span class="badge badge-info">Cost Calculator</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            @if($query->status === 'new')
                                <span class="badge badge-warning">New</span>
                            @elseif($query->status === 'in_progress')
                                <span class="badge badge-primary">In Progress</span>
                            @elseif($query->status === 'resolved')
                                <span class="badge badge-success">Resolved</span>
                            @else
                                <span class="badge badge-secondary">Closed</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <strong>Date:</strong> {{ $query->created_at->format('M d, Y h:i A') }}
                        </div>
                        <div class="mb-2">
                            <strong>Name:</strong> {{ $query->full_name }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> <a href="mailto:{{ $query->email }}">{{ $query->email }}</a>
                        </div>
                        @if($query->phone)
                            <div class="mb-2">
                                <strong>Phone:</strong> <a href="tel:{{ $query->phone }}">{{ $query->phone }}</a>
                            </div>
                        @endif
                        @if($query->company)
                            <div class="mb-2">
                                <strong>Company:</strong> {{ $query->company }}
                            </div>
                        @endif
                        <div class="mb-2">
                            <strong>Source:</strong> {{ ucfirst($query->source ?? 'N/A') }}
                        </div>
                        @if($query->subject)
                            <div class="mb-2">
                                <strong>Subject:</strong> {{ $query->subject }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Manage Query</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('queries.update', $query) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="new" {{ $query->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="in_progress" {{ $query->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $query->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $query->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            
                            {{-- User assignment feature commented out as requested
                            <div class="form-group">
                                <label>Assign To</label>
                                <select class="form-control" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $query->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            --}}
                            
                            <div class="form-group">
                                <label>Admin Notes</label>
                                <textarea class="form-control" name="admin_notes" rows="3">{{ $query->admin_notes }}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Update Query</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Original Message</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light mb-4">
                            {!! nl2br(e($query->description)) !!}
                        </div>
                        
                        @if($query->attachments->count() > 0)
                            <div class="mt-4">
                                <h6 class="mb-2">Attachments:</h6>
                                <div class="row">
                                    @foreach($query->attachments as $attachment)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="mb-2">{{ $attachment->file_name }}</div>
                                                    <a href="{{ route('query.attachments.download', $attachment) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Send Response</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('queries.responses.store', $query) }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label>Email Message</label>
                                <textarea class="form-control" name="message" rows="6" required></textarea>
                                <small class="form-text text-muted">This message will be sent to {{ $query->email }}</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Send Email Response</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 