@extends('layouts.main')

@section('title', 'Create Role')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Role</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
            <div class="breadcrumb-item">Create</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Role</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Permissions</label>
                                @if($permissions->count() > 0)
                                    @error('permissions')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="row">
                                        @foreach($permissions as $module => $modulePermissions)
                                            <div class="col-md-6 mb-4">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h4>{{ ucfirst(str_replace('_', ' ', $module)) }}</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="selectgroup selectgroup-pills">
                                                                @foreach($modulePermissions as $permission)
                                                                    <label class="selectgroup-item">
                                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="selectgroup-input" {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                                                                        <span class="selectgroup-button">{{ str_replace($module.'_', '', $permission->key) }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        No permissions found. Please create permissions first.
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Create Role</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 