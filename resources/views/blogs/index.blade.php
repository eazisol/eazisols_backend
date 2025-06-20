@extends('layouts.main')
@section('title', 'Blogs')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Blogs</h1>
        <div class="section-header-button">
            <a href="{{ route('blogs.create') }}" class="btn btn-primary">Add New</a>
        </div>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Blogs</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Blogs</h2>
        <p class="section-lead">
            Manage all blog posts here.
        </p>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Search & Filter</h4>
                        <div class="card-header-action">
                            <a data-collapse="#search-filter-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="collapse" id="search-filter-collapse">
                        <div class="card-body">
                            <form action="{{ route('blogs.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Search</label>
                                            <input type="text" name="search" class="form-control" placeholder="Search by title, description, etc." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select name="category" class="form-control">
                                                <option value="">All Categories</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">All Statuses</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Sort By</label>
                                            <div class="d-flex">
                                                <select name="sort" class="form-control mr-2">
                                                    <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                                                    <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>Category</option>
                                                </select>
                                                <select name="direction" class="form-control">
                                                    <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary mr-2">Apply Filters</button>
                                            <a href="{{ route('blogs.index') }}" class="btn btn-light">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Blogs</h4>
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
                                        <th>ID</th>
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
                                        <td>{{ $blog->id }}</td>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Fix for sidebar and header issues
    $("[data-toggle='sidebar']").trigger('click');
    setTimeout(function() {
        $("[data-toggle='sidebar']").trigger('click');
    }, 100);
    
    // Initialize sidebar dropdown functionality
    $('.main-sidebar .sidebar-menu li a.has-dropdown').on('click', function() {
        var me = $(this);
        me.parent().find('> .dropdown-menu').slideToggle(500);
        return false;
    });
    
    // Set active sidebar menu item
    $('.main-sidebar .sidebar-menu li').removeClass('active');
    $('.main-sidebar .sidebar-menu li a[href="' + window.location.href + '"]').parent().addClass('active');
    $('.main-sidebar .sidebar-menu li a[href="' + window.location.pathname + '"]').parent().addClass('active');

    // Original blog index page script
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

    // Initialize select2
    if($.fn.select2) {
        $('select').select2();
    }

    // Toggle search/filter panel
    $('[data-collapse]').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('collapse');
        $(target).collapse('toggle');
    });
});
</script>
@endsection 