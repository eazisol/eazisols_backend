@extends('layouts.main')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Category Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></div>
            <div class="breadcrumb-item">{{ $category->name }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $category->name }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ $category->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $category->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td>{{ $category->slug }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td>
                                            <span class="badge badge-{{ $category->type == 'blog' ? 'info' : 'success' }}">
                                                {{ ucfirst($category->type) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge badge-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($category->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $category->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $category->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Related Content</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            @if($category->type == 'blog')
                                                <a href="{{ route('blogs.index', ['category' => $category->name]) }}" class="btn btn-info">
                                                    <i class="fas fa-newspaper"></i> View Blog Posts
                                                </a>
                                            @else
                                                <a href="{{ route('careers.index') }}" class="btn btn-success">
                                                    <i class="fas fa-briefcase"></i> View Career Posts
                                                </a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
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