@extends('layouts.main')
@section('title', 'Blogs')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Blogs</h1>
        {{-- <div class="section-header-button">
            <a href="{{ route('blogs.create') }}" class="btn btn-primary">Add New</a>
        </div> --}}
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Blogs</div>
        </div>
    </div>

    <div class="section-body">
        

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Blogs</h4>
                        <div class="card-header-action">
                            <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Blogs
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3 px-4 pt-3">
                        <div class="col-md-12">
                            <form action="{{ route('blogs.index') }}" method="GET" class="form-inline">
                                <div class="input-group mb-2 mr-sm-2">
                                    <input type="text" name="search" class="form-control" placeholder="Search blogs..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                
                                <div class="form-group mr-2 mb-2">
                                    <select name="category" class="form-control" style="min-width: 150px;">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mr-2 mb-2">
                                    <select name="status" class="form-control" style="min-width: 150px;">
                                        <option value="">All Statuses</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary mb-2 mr-2">
                                    <i class="fas fa-filter mr-1"></i> Apply Filters
                                </button>
                                
                                @if(request()->anyFilled(['search', 'category', 'status']))
                                    <a href="{{ route('blogs.index') }}" class="btn btn-light mb-2">
                                        <i class="fas fa-times mr-1"></i> Clear Filters
                                    </a>
                                @endif
                                
                                <div class="ml-auto mb-2">
                                    <div class="btn-group">
                                        <a href="{{ route('blogs.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}"
                                           class="btn {{ request('sort', 'created_at') == 'created_at' && request('direction', 'desc') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                            <i class="fas fa-sort-amount-down mr-1"></i> Newest
                                        </a>
                                        <a href="{{ route('blogs.index', ['sort' => 'created_at', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}"
                                           class="btn {{ request('sort') == 'created_at' && request('direction') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                            <i class="fas fa-sort-amount-up mr-1"></i> Oldest
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="px-4">
                        @if(request()->anyFilled(['search', 'category', 'status']))
                            <div class="active-filters mb-3">
                                <span class="font-weight-bold mr-2">Active Filters:</span>
                                @if(request('search'))
                                    <span class="badge badge-info mr-1">
                                        Search: "{{ request('search') }}"
                                    </span>
                                @endif
                                
                                @if(request('category'))
                                    <span class="badge badge-info mr-1">
                                        Category: {{ request('category') }}
                                    </span>
                                @endif
                                
                                @if(request('status'))
                                    <span class="badge badge-info mr-1">
                                        Status: {{ ucfirst(request('status')) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="blogs-table">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Thumbnail</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blogs as $blog)
                                    <tr>
                                        {{-- <td>{{ $blog->id }}</td> --}}
                                        <td>
                                            @if($blog->thumbnail)
                                                <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}" width="50">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $blog->title }}</td>
                                        <td>{{ $blog->category }}</td>
                                        <td>
                                            <div class="badge badge-{{ $blog->status == 'published' ? 'success' : 'warning' }}">
                                                {{ ucfirst($blog->status) }}
                                            </div>
                                        </td>
                                        <td>{{ $blog->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $blog->id }}"
                                                data-title="{{ $blog->title }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $blog->id }}" action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-none">
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
                            {{ $blogs->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<!-- jQuery (if not already loaded) -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: 'Delete Blog',
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