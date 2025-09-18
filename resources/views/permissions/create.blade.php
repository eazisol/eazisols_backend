@extends('layouts.main')

@section('title', 'Create Permission')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Permission</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></div>
            <div class="breadcrumb-item">Create</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Permission</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('permissions.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Module</label>
                                        <select class="form-control" id="module" required>
                                            <option value="dash">Dashboard (dash)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Section</label>
                                        <select class="form-control" id="section" required>
                                            <option value="">Select section</option>
                                            <option value="users">Users</option>
                                            <option value="roles">Roles</option>
                                            <option value="permissions">Permissions</option>
                                            <option value="blogs">Blogs</option>
                                            <option value="careers">Careers</option>
                                            <option value="categories">Categories</option>
                                            <option value="queries">Queries</option>
                                            <option value="case_studies">Case Studies</option>
                                            <option value="attendance">Attendance</option>
                                            <option value="leaves">Leaves</option>
                                            <option value="settings">Settings</option>
                                            <option value="dashboard">Dashboard</option>
                                            <option value="applied_jobs">Applied Jobs</option>
                                            <option value="locations">Locations</option>
                                            <option value="reminders">Monthly Reminders</option>
                                            <option value="interviews">Interviews</option>
                                            <option value="other">Other (custom)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Action</label>
                                        <input type="text" class="form-control" id="action" placeholder="create, edit, delete, etc." required>
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-2" id="custom-section-container" style="display: none;">
                                    <div class="form-group">
                                        <label>Custom Section Name</label>
                                        <input type="text" class="form-control" id="custom-section" placeholder="Enter custom section name">
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="form-group">
                                        <label>Generated Permission Key</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light" id="preview-key" readonly>
                                            <input type="hidden" name="key" id="permission-key">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            </div>
                                        </div>
                                        @error('key')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Create Permission</button>
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

@section('scripts')
<script>
    $(document).ready(function() {
        function updatePermissionKey() {
            var module = $('#module').val();
            var section = $('#section').val();
            var action = $('#action').val();
            
            if (section === 'other') {
                section = $('#custom-section').val();
            }
            
            if (module && section && action) {
                var permissionKey = module + '_' + section + '_' + action;
                $('#preview-key').val(permissionKey);
                $('#permission-key').val(permissionKey);
            } else {
                $('#preview-key').val('');
                $('#permission-key').val('');
            }
        }
        
        $('#module, #section, #action, #custom-section').on('input change', function() {
            updatePermissionKey();
        });
        
        $('#section').on('change', function() {
            if ($(this).val() === 'other') {
                $('#custom-section-container').slideDown();
                $('#custom-section').prop('required', true);
            } else {
                $('#custom-section-container').slideUp();
                $('#custom-section').prop('required', false);
            }
            updatePermissionKey();
        });
    });
</script>
@endsection 