@extends('layouts.main')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Careers</h1>
        <div class="section-header-button">
            <a href="{{ route('careers.create') }}" class="btn btn-primary">Add New</a>
        </div>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Careers</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Careers</h2>
        <p class="section-lead">
            Manage all career opportunities here.
        </p>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Careers</h4>
                        <div class="card-header-form">
                            <form action="{{ route('careers.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by title or location" value="{{ request('search') }}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mb-3 px-4 pt-3">
                        <div class="col-md-6">
                            <form action="{{ route('careers.index') }}" method="GET" class="form-inline">
                                <div class="form-group mr-2 mb-2">
                                    <select name="type" class="form-control">
                                        <option value="">All Types</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mr-2 mb-2">
                                    <select name="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                            </form>
                        </div>

                        <div class="col-md-6 text-right">
                            <div class="btn-group">
                                <a href="{{ route('careers.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}"
                                   class="btn {{ request('sort', 'created_at') == 'created_at' && request('direction', 'desc') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Newest First
                                </a>
                                <a href="{{ route('careers.index', ['sort' => 'created_at', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}"
                                   class="btn {{ request('sort') == 'created_at' && request('direction') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Oldest First
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="careers-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($careers as $career)
                                    <tr>
                                        <td>{{ $career->id }}</td>
                                        <td>{{ $career->title }}</td>
                                        <td>{{ $career->location }}</td>
                                        <td>{{ $career->type }}</td>
                                        <td>{{ $career->application_deadline ? $career->application_deadline->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="badge badge-{{ $career->status == 'active' ? 'success' : ($career->status == 'filled' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($career->status) }}
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('careers.edit', $career) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $career->id }}"
                                                data-title="{{ $career->title }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $career->id }}" action="{{ route('careers.destroy', $career) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $careers->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: 'Delete Career',
            text: `Are you sure you want to delete "${title}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-${id}`).submit();
            }
        });
    });
});
</script>

@endsection