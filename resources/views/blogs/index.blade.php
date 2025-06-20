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
});
</script>
@endsection 