@extends('layouts.main')

@section('title', 'Permissions Management')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Permissions Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Permissions</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Manage Permissions</h2>
        <p class="section-lead">Manage system permissions that can be assigned to roles.</p>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Permissions List</h4>
                        <div class="card-header-action">
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Permission
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($permissions->count() > 0)
                            <div class="accordion" id="permissionsAccordion">
                                @foreach($permissions as $module => $modulePermissions)
                                    <div class="card">
                                        <div class="card-header" id="heading{{ Str::slug($module) }}">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" 
                                                        data-target="#collapse{{ Str::slug($module) }}" aria-expanded="true" 
                                                        aria-controls="collapse{{ Str::slug($module) }}">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $module)) }}</strong> 
                                                    <span class="badge badge-info">{{ $modulePermissions->count() }}</span>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapse{{ Str::slug($module) }}" class="collapse" 
                                             aria-labelledby="heading{{ Str::slug($module) }}" 
                                             data-parent="#permissionsAccordion">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Key</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($modulePermissions as $permission)
                                                                <tr>
                                                                    <td>{{ $permission->id }}</td>
                                                                    <td>{{ $permission->key }}</td>
                                                                    <td>
                                                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-info">
                                                                            <i class="fas fa-edit"></i> Edit
                                                                        </a>
                                                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this permission?')">
                                                                                <i class="fas fa-trash"></i> Delete
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                No permissions found. Please create some permissions.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Show the first module by default
        $('#permissionsAccordion .collapse:first').addClass('show');
    });
</script>
@endsection 