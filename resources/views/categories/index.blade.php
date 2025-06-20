@extends('layouts.main')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Categories</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Categories</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Categories</h4>
                        <div class="card-header-action">
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Category
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <form action="{{ route('categories.index') }}" method="GET" class="form-inline">
                                    <div class="input-group mb-2 mr-2">
                                        <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
                                        <select name="type" class="form-control">
                                            <option value="">All Types</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                    {{ ucfirst($type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
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
                                    @if(request()->anyFilled(['search', 'type', 'status']))
                                        <a href="{{ route('categories.index') }}" class="btn btn-light mb-2 ml-1">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    @endif
                                </form>
                            </div>
                            
                            <div class="col-md-4 text-right">
                                <div class="btn-group">
                                    <a href="{{ route('categories.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}" 
                                       class="btn {{ request('sort', 'created_at') == 'created_at' && request('direction', 'desc') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Newest
                                    </a>
                                    <a href="{{ route('categories.index', ['sort' => 'name', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}" 
                                       class="btn {{ request('sort') == 'name' && request('direction') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        A-Z
                                    </a>
                                </div>
                            </div>
                        </div>

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
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            <td>
                                                <span class="badge badge-{{ $category->type == 'blog' ? 'info' : 'success' }}">
                                                    {{ ucfirst($category->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($category->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $category->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $category->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No categories found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $categories->appends(request()->query())->links() }}
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
    $(document).ready(function() {
        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });
    });
</script>
@endsection 