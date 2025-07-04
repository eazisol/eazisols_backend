@extends('layouts.main')

@section('title', 'Edit Role')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Role</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Role: {{ $role->name }}</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Permissions <small class="text-muted">(Dashboard access is enabled by default)</small></label>
                                @if($permissions->count() > 0)
                                    @error('permissions')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Permissions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($permissions as $module => $modulePermissions)
                                                <tr>
                                                    <td class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $module)) }}</td>
                                                    <td>
                                                        <div class="selectgroup selectgroup-pills">
                                                            @foreach($modulePermissions as $permission)
                                                                <label class="selectgroup-item mr-1">
                                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="selectgroup-input" 
                                                                        {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) || 
                                                                           (in_array($permission->id, $rolePermissions)) ? 'checked' : '' }}>
                                                                    <span class="selectgroup-button">{{ str_replace($module.'_', '', $permission->key) }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        No permissions found. Please create permissions first.
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Role</button>
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