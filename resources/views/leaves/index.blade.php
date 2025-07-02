@extends('layouts.main')

@section('title')
    Leave Requests
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Leave Requests</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Leave Requests</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Leave Requests</h4>
                        <div class="card-header-action">
                            <a href="{{ route('leaves.create') }}" class="btn btn-primary">Apply for Leave</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                                       href="{{ route('leaves.index', ['status' => 'all']) }}">
                                        All <span class="badge badge-white">{{ $counts['pending'] + $counts['approved'] + $counts['rejected'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                                       href="{{ route('leaves.index', ['status' => 'pending']) }}">
                                        Pending <span class="badge badge-warning">{{ $counts['pending'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                                       href="{{ route('leaves.index', ['status' => 'approved']) }}">
                                        Approved <span class="badge badge-success">{{ $counts['approved'] }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                                       href="{{ route('leaves.index', ['status' => 'rejected']) }}">
                                        Rejected <span class="badge badge-danger">{{ $counts['rejected'] }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

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

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        @if($isAdmin)
                                            <th>Employee</th>
                                        @endif
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Duration</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leaves as $leave)
                                        <tr>
                                            @if($isAdmin)
                                                <td>{{ $leave->user->name }}</td>
                                            @endif
                                            <td>{{ $leave->start_date->format('d M Y') }}</td>
                                            <td>{{ $leave->end_date->format('d M Y') }}</td>
                                            <td>{{ $leave->duration }} day(s)</td>
                                            <td>{{ \Illuminate\Support\Str::limit($leave->reason, 50) }}</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($leave->status === 'pending')
                                                    @if($isAdmin)
                                                        <div class="dropdown d-inline">
                                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown{{ $leave->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <form action="{{ route('leaves.update-status', $leave) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="approved">
                                                                    <button type="submit" class="dropdown-item">Approve</button>
                                                                </form>
                                                                <form action="{{ route('leaves.update-status', $leave) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="rejected">
                                                                    <button type="submit" class="dropdown-item">Reject</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(Auth::id() === $leave->user_id || $isAdmin)
                                                        <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this leave request?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $isAdmin ? '7' : '6' }}" class="text-center">No leave requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $leaves->appends(['status' => $status])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 