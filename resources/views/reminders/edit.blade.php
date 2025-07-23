@extends('layouts.main')

@section('title', 'Edit Reminder')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Reminder</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('reminders.index') }}">Reminders</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Reminder Details</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reminders.update', $reminder) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="title">Reminder Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $reminder->title) }}" 
                                           placeholder="e.g., Salary Payment Reminder"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Detailed description of what needs to be done..."
                                              required>{{ old('description', $reminder->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="day_of_month">Day of Month <span class="text-danger">*</span></label>
                                            <select class="form-control @error('day_of_month') is-invalid @enderror" 
                                                    id="day_of_month" 
                                                    name="day_of_month" 
                                                    required>
                                                <option value="">Select Day</option>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}" {{ old('day_of_month', $reminder->day_of_month) == $i ? 'selected' : '' }}>
                                                        {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('day_of_month')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                If a month doesn't have this day (e.g., Feb 30th), it will use the last day of that month.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time_of_day">Time <span class="text-danger">*</span></label>
                                            <input type="time" 
                                                   class="form-control @error('time_of_day') is-invalid @enderror" 
                                                   id="time_of_day" 
                                                   name="time_of_day" 
                                                   value="{{ old('time_of_day', \Carbon\Carbon::parse($reminder->time_of_day)->format('H:i')) }}" 
                                                   required>
                                            @error('time_of_day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Important:</h6>
                                    <p class="mb-0">
                                        Updating this reminder will recalculate the next trigger date and reset the notification status. 
                                        The next reminder will be scheduled based on your new settings.
                                    </p>
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Reminder
                                    </button>
                                    <a href="{{ route('reminders.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-4 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Current Schedule</h4>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 d-flex justify-content-between">
                                    <span>Current Day:</span>
                                    <strong>{{ $reminder->day_with_suffix }}</strong>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between">
                                    <span>Current Time:</span>
                                    <strong>{{ $reminder->formatted_time }}</strong>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between">
                                    <span>Next Reminder:</span>
                                    <strong>{{ $reminder->next_trigger_at->format('M j, Y g:i A') }}</strong>
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between">
                                    <span>Status:</span>
                                    <span class="badge badge-{{ $reminder->is_active ? 'success' : 'secondary' }}">
                                        {{ $reminder->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Quick Actions</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reminders.test', $reminder) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-paper-plane"></i> Send Test Email
                                </button>
                            </form>
                            
                            <form action="{{ route('reminders.toggle-status', $reminder) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $reminder->is_active ? 'warning' : 'success' }} btn-block">
                                    <i class="fas {{ $reminder->is_active ? 'fa-pause' : 'fa-play' }}"></i> 
                                    {{ $reminder->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            
                            <button type="button" class="btn btn-danger btn-block delete-btn"
                                data-id="{{ $reminder->id }}"
                                data-title="{{ $reminder->title }}">
                                <i class="fas fa-trash"></i> Delete Reminder
                            </button>
                            <form id="delete-form-{{ $reminder->id }}" action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
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