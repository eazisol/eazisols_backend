@extends('layouts.main')
@section('title', 'Blogs')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $blog->title }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></div>
            <div class="breadcrumb-item">{{ Str::limit($blog->title, 30) }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Blog Details</h4>
                        <div class="card-header-action">
                            <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger delete-btn" 
                                data-id="{{ $blog->id }}"
                                data-title="{{ $blog->title }}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <form id="delete-form-{{ $blog->id }}" action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="200">Title</th>
                                        <td>{{ $blog->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td>{{ $blog->category }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <div class="badge badge-{{ $blog->status == 'published' ? 'success' : 'warning' }}">
                                                {{ ucfirst($blog->status) }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created Date</th>
                                        <td>{{ $blog->created_at->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $blog->updated_at->format('F d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                @if($blog->thumbnail)
                                    <div class="text-center">
                                        <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}" class="img-fluid rounded">
                                    </div>
                                @else
                                    <div class="alert alert-light text-center">
                                        <i class="fas fa-image fa-3x mb-3"></i>
                                        <p>No thumbnail image</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <h5>Blog Content</h5>
                        <div class="blog-content border rounded p-3">
                            {!! $blog->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if($relatedBlogs->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Related Blogs</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($relatedBlogs as $relatedBlog)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        @if($relatedBlog->thumbnail)
                                            <img src="{{ asset($relatedBlog->thumbnail) }}" alt="{{ $relatedBlog->title }}" class="img-fluid mb-3" style="max-height: 150px; width: 100%; object-fit: cover;">
                                        @endif
                                        <h5 class="card-title">{{ $relatedBlog->title }}</h5>
                                        <p class="text-muted">{{ $relatedBlog->created_at->format('M d, Y') }}</p>
                                        <a href="{{ route('blogs.show', $relatedBlog) }}" class="btn btn-primary btn-sm">View Details</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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