@extends('layouts.main')

@section('title')
    Leave History
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Leave History</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Leave History</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All My Leave Requests</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
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
                                                <a href="{{ route('leaves.show', $leave->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No leave requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $leaves->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection