@extends('layouts.main')

@section('title', 'Monthly Reminders')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Monthly Reminders</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Reminders</div>
        </div>
    </div>

    <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Your Monthly Reminders</h4>
                            
                        </div>
                        <div class="card-body">
                            @if($reminders->count() > 0)
                            <div class="card-header-action">
                                <a href="{{ route('reminders.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add New Reminder
                                </a>
                            </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Schedule</th>
                                                <th>Next Reminder</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reminders as $reminder)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $reminder->title }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($reminder->description, 50) }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $reminder->day_with_suffix }}</span>
                                                        of every month
                                                        <br>
                                                        <small class="text-muted">at {{ $reminder->formatted_time }}</small>
                                                    </td>
                                                    <td>
                                                        @if($reminder->is_active)
                                                            <span class="text-success">
                                                                {{ $reminder->next_trigger_at->format('M j, Y') }}
                                                                <br>
                                                                <small>{{ $reminder->next_trigger_at->format('g:i A') }}</small>
                                                            </span>
                                                        @else
                                                            <span class="text-muted">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($reminder->is_active)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('reminders.show', $reminder) }}" 
                                                               class="btn btn-sm btn-info" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('reminders.edit', $reminder) }}" 
                                                               class="btn btn-sm btn-warning" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('reminders.toggle-status', $reminder) }}" 
                                                                  method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" 
                                                                        class="btn btn-sm {{ $reminder->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                                        title="{{ $reminder->is_active ? 'Deactivate' : 'Activate' }}">
                                                                    <i class="fas {{ $reminder->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('reminders.test', $reminder) }}" 
                                                                  method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-primary" 
                                                                        title="Send Test Email">
                                                                    <i class="fas fa-paper-plane"></i>
                                                                </button>
                                                            </form>
                                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                                data-id="{{ $reminder->id }}"
                                                                data-title="{{ $reminder->title }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <form id="delete-form-{{ $reminder->id }}" action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-center">
                                    {{ $reminders->links() }}
                                </div>
                            @else
                                <div class="empty-state" data-height="400">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <h2>No reminders yet</h2>
                                    <p class="lead">
                                        You haven't created any monthly reminders yet. Create your first reminder to get started!
                                    </p>
                                    <a href="{{ route('reminders.create') }}" class="btn btn-primary mt-4">
                                        <i class="fas fa-plus"></i> Create Your First Reminder
                                    </a>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: 'Delete Reminder',
            text: `Are you sure you want to delete "${title}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-${id}`).submit();
            }
        });
    });
});
</script>
@endsection