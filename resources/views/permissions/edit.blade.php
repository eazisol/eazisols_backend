@extends('layouts.main')

@section('title', 'Edit Permission')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Permission</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Permission</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="d-block">Current Permission Key</label>
                                        <div class="border rounded p-3 bg-light">
                                            <code class="h5">{{ $permission->key }}</code>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <div class="form-group">
                                        <label for="key">New Permission Key</label>
                                        <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key', $permission->key) }}" required>
                                        <small class="form-text text-muted">
                                            Format should follow: <code>module_section_action</code> (e.g., dash_users_create)
                                        </small>
                                        @error('key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle mr-1"></i> 
                                <strong>Warning:</strong> Changing permission keys can affect existing roles. Make sure to update any roles using this permission.
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Update Permission</button>
                                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 