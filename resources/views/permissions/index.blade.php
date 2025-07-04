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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Permissions List</h4>
                        <div class="card-header-action">
                            <a href="{{ route('permissions.create') }}" class="btn btn-icon btn-primary">
                                <i class="fas fa-plus"></i> New Permission
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                            <div class="alert alert-success m-3">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger m-3">{{ session('error') }}</div>
                        @endif

                        @if($permissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="permissions-table">
                                    <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>Permission Key</th>
                                            <th class="text-center" style="width: 120px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $module => $modulePermissions)
                                            @foreach($modulePermissions as $permission)
                                                <tr>
                                                    @if($loop->first)
                                                    <td class="font-weight-bold" rowspan="{{ count($modulePermissions) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $module)) }}
                                                    </td>
                                                    @endif
                                                    <td>{{ $permission->key }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary mr-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this permission?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info m-3">
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
        $('#permissions-table').DataTable({
            "ordering": true,
            "info": false,
            "paging": true,
            "pageLength": 25,
            "lengthChange": false,
            "searching": true,
            "columnDefs": [
                { "orderable": false, "targets": 2 }
            ]
        });
    });
</script>
@endsection 