@extends('layouts.main')

@section('title')
    Designations
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Designations</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Designations</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Designation List</h4>
                        <div class="card-header-action">
                            <a href="{{ route('departments.create') }}" class="btn btn-primary mr-2">Add Department</a>
                            <a href="{{ route('designations.create') }}" class="btn btn-success">Add Designation</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form action="{{ route('designations.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Search by designation name..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($designations as $designation)
                                        <tr>
                                            <td>{{ $designation->name }}</td>
                                            <td>{{ $designation->department->name ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-sm btn-info">Edit</a>
                                                <form action="{{ route('designations.destroy', $designation->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this designation?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">No designations found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $designations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 