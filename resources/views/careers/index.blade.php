@extends('layouts.main')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Careers</h1>
        <div class="section-header-button">
            <a href="{{ route('careers.create') }}" class="btn btn-primary">Add New</a>
        </div>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Careers</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Careers</h2>
        <p class="section-lead">
            Manage all career opportunities here.
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
                            <form action="{{ route('careers.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Search</label>
                                            <input type="text" name="search" class="form-control" placeholder="Search by title, description, etc." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select name="type" class="form-control">
                                                <option value="">All Types</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Location</label>
                                            <select name="location" class="form-control">
                                                <option value="">All Locations</option>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Department filter removed
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <select name="department" class="form-control">
                                                <option value="">All Departments</option>
                                                @foreach($departments as $department)
                                                    @if($department)
                                                        <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>{{ $department }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    --}}
                                    <div class="col-md-2">
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
                                                    <option value="location" {{ request('sort') == 'location' ? 'selected' : '' }}>Location</option>
                                                    <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Type</option>
                                                    <option value="application_deadline" {{ request('sort') == 'application_deadline' ? 'selected' : '' }}>Deadline</option>
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
                                            <a href="{{ route('careers.index') }}" class="btn btn-light">Reset</a>
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
                        <h4>All Careers</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="careers-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($careers as $career)
                                    <tr>
                                        <td>{{ $career->id }}</td>
                                        <td>{{ $career->title }}</td>
                                        <td>{{ $career->location }}</td>
                                        <td>{{ $career->type }}</td>
                                        <td>{{ $career->application_deadline ? $career->application_deadline->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="badge badge-{{ $career->status == 'active' ? 'success' : ($career->status == 'filled' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($career->status) }}
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('careers.edit', $career) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal" 
                                                data-id="{{ $career->id }}"
                                                data-title="{{ $career->title }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $career->id }}" action="{{ route('careers.destroy', $career) }}" method="POST" class="d-none">
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
                            {{ $careers->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="deleteModalLabel">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this career?</p>
                <p class="font-weight-bold" id="deleteItemName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .modal-backdrop {
        opacity: 0.5 !important;
    }
    
    .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
        z-index: 1050;
    }
    
    .modal-content {
        border-radius: 0.3rem;
        box-shadow: 0 5px 15px rgba(0,0,0,.5);
        position: relative;
    }
    
    .modal {
        z-index: 1040;
    }
    
    .modal-header, .modal-body, .modal-footer {
        position: relative;
        z-index: 1060;
    }
    
    .btn {
        position: relative;
        z-index: 1070;
    }
</style>
<script>
    $(document).ready(function() {
        // Initialize select2 for better dropdown experience
        $('select').select2();
        
        // Toggle search/filter panel
        $('[data-collapse]').on('click', function(e) {
            e.preventDefault();
            const target = $(this).data('collapse');
            $(target).collapse('toggle');
        });
        
        // Handle delete button click
        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            
            $('#deleteItemName').text(title);
            
            // Reset any previous event handlers
            $('#confirmDeleteBtn').off('click');
            
            // Add new click handler for this specific item
            $('#confirmDeleteBtn').on('click', function() {
                $(`#delete-form-${id}`).submit();
            });
            
            // Show the modal with specific options
            $('#deleteModal').modal({
                backdrop: true,
                keyboard: true,
                focus: true,
                show: true
            });
        });
        
        // Ensure the modal can be closed properly
        $('.close, button[data-dismiss="modal"]').on('click', function() {
            $('#deleteModal').modal('hide');
        });
    });
    
    // Fix for modal backdrop issue
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });
</script>
@endsection 