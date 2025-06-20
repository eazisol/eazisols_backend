@extends('layouts.main')

@section('title', 'Queries')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Queries</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Queries</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Filters</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item"><a href="{{ route('queries.index') }}" class="nav-link {{ $type === 'all' && $status === 'all' ? 'active' : '' }}">All Queries <span class="badge badge-white">{{ $counts['all'] }}</span></a></li>
                            <li class="nav-item"><a href="{{ route('queries.index', ['type' => 'contact']) }}" class="nav-link {{ $type === 'contact' ? 'active' : '' }}">Contact Queries <span class="badge badge-primary">{{ $counts['contact'] }}</span></a></li>
                            <li class="nav-item"><a href="{{ route('queries.index', ['type' => 'cost_calculator']) }}" class="nav-link {{ $type === 'cost_calculator' ? 'active' : '' }}">Cost Calculator Queries <span class="badge badge-info">{{ $counts['cost_calculator'] }}</span></a></li>
                        </ul>
                        <div class="mt-4 mb-2 font-weight-bold">Status</div>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item"><a href="{{ route('queries.index', ['type' => $type, 'status' => 'new']) }}" class="nav-link {{ $status === 'new' ? 'active' : '' }}">New <span class="badge badge-warning">{{ $counts['new'] }}</span></a></li>
                            <li class="nav-item"><a href="{{ route('queries.index', ['type' => $type, 'status' => 'in_progress']) }}" class="nav-link {{ $status === 'in_progress' ? 'active' : '' }}">In Progress <span class="badge badge-primary">{{ $counts['in_progress'] }}</span></a></li>
                            <li class="nav-item"><a href="{{ route('queries.index', ['type' => $type, 'status' => 'resolved']) }}" class="nav-link {{ $status === 'resolved' ? 'active' : '' }}">Resolved <span class="badge badge-success">{{ $counts['resolved'] }}</span></a></li>
                            <li class="nav-item"><a href="{{ route('queries.index', ['type' => $type, 'status' => 'closed']) }}" class="nav-link {{ $status === 'closed' ? 'active' : '' }}">Closed <span class="badge badge-secondary">{{ $counts['closed'] }}</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            @if($type === 'contact')
                                Contact Queries
                            @elseif($type === 'cost_calculator')
                                Cost Calculator Queries
                            @else
                                All Queries
                            @endif
                            
                            @if($status !== 'all')
                                - {{ ucfirst(str_replace('_', ' ', $status)) }}
                            @endif
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($queries as $query)
                                        <tr>
                                            <td>{{ $query->id }}</td>
                                            <td>{{ $query->name }}</td>
                                            <td>
                                                @if($query->type === 'contact')
                                                    <span class="badge badge-primary">Contact</span>
                                                @else
                                                    <span class="badge badge-info">Cost Calculator</span>
                                                @endif
                                            </td>
                                            <td>{{ $query->subject ?? 'N/A' }}</td>
                                            <td>
                                                @if($query->status === 'new')
                                                    <span class="badge badge-warning">New</span>
                                                @elseif($query->status === 'in_progress')
                                                    <span class="badge badge-primary">In Progress</span>
                                                @elseif($query->status === 'resolved')
                                                    <span class="badge badge-success">Resolved</span>
                                                @else
                                                    <span class="badge badge-secondary">Closed</span>
                                                @endif
                                            </td>
                                            <td>{{ $query->assignedUser ? $query->assignedUser->name : 'Unassigned' }}</td>
                                            <td>{{ $query->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('queries.show', $query) }}" class="btn btn-primary btn-sm">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No queries found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $queries->appends(['type' => $type, 'status' => $status])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 