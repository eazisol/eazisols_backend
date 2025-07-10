@extends('layouts.main')
@section('title', 'Job Applications')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Job Applications</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Job Applications</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Applications</h4>
                        <div class="card-header-form">
                            <form action="{{ route('applied-jobs.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name, email or job title" value="{{ request('search') }}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form action="{{ route('applied-jobs.index') }}" method="GET" class="form-inline">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <input type="text" name="search" class="form-control" placeholder="Search by name, email or job title" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mr-2 mb-2">
                                        <select name="career_id" class="form-control" style="min-width: 150px;">
                                            <option value="">All Jobs</option>
                                            @foreach($careers as $id => $title)
                                                <option value="{{ $id }}" {{ request('career_id') == $id ? 'selected' : '' }}>
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2 mb-2">
                                        <select name="status" class="form-control" style="min-width: 150px;">
                                            <option value="">All Statuses</option>
                                            @foreach($statuses as $value => $label)
                                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary mb-2 mr-2">
                                        <i class="fas fa-filter mr-1"></i> Apply Filters
                                    </button>
                                    
                                    @if(request()->anyFilled(['search', 'career_id', 'status']))
                                        <a href="{{ route('applied-jobs.index') }}" class="btn btn-light mb-2">
                                            <i class="fas fa-times mr-1"></i> Clear Filters
                                        </a>
                                    @endif
                                    
                                    <div class="ml-auto mb-2">
                                        <div class="btn-group">
                                            <a href="{{ route('applied-jobs.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}" 
                                               class="btn {{ request('sort', 'created_at') == 'created_at' && request('direction', 'desc') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                                <i class="fas fa-sort-amount-down mr-1"></i> Newest
                                            </a>
                                            <a href="{{ route('applied-jobs.index', ['sort' => 'created_at', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}" 
                                               class="btn {{ request('sort') == 'created_at' && request('direction') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                                <i class="fas fa-sort-amount-up mr-1"></i> Oldest
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="px-2">
                            @if(request()->anyFilled(['search', 'career_id', 'status']))
                                <div class="active-filters mb-3">
                                    <span class="font-weight-bold mr-2">Active Filters:</span>
                                    @if(request('search'))
                                        <span class="badge badge-info mr-1">
                                            Search: "{{ request('search') }}"
                                        </span>
                                    @endif
                                    
                                    @if(request('career_id'))
                                        <span class="badge badge-info mr-1">
                                            Position: {{ $careers[request('career_id')] ?? 'Unknown' }}
                                        </span>
                                    @endif
                                    
                                    @if(request('status'))
                                        <span class="badge badge-info mr-1">
                                            Status: {{ $statuses[request('status')] ?? 'Unknown' }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Position Applied</th>
                                        <th>Status</th>
                                        <th>Applied On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appliedJobs as $application)
                                        <tr>
                                            {{-- <td>{{ $application->id }}</td> --}}
                                            <td>{{ $application->name }}</td>
                                            <td>{{ $application->email }}</td>
                                            <td>{{ $application->career->title ?? 'N/A' }}</td>
                                            <td>
                                                <div class="badge badge-{{ 
                                                    $application->status == 'pending' ? 'warning' : 
                                                    ($application->status == 'viewed' ? 'info' : 
                                                    ($application->status == 'approved' ? 'success' : 'danger')) 
                                                }}">
                                                    {{ ucfirst($application->status) }}
                                                </div>
                                            </td>
                                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('applied-jobs.show', $application) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(auth()->user()->hasPermission('dash_applied_jobs_delete'))
                                                <form action="{{ route('applied-jobs.destroy', $application) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-button" data-id="{{ $application->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No job applications found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appliedJobs->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'This application will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        });
    });
});
</script>
@endsection