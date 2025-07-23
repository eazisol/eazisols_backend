@extends('layouts.main')

@section('title', 'Reminder Details')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Reminder Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('reminders.index') }}">Reminders</a></div>
            <div class="breadcrumb-item">{{ $reminder->title }}</div>
        </div>
    </div>

    <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $reminder->title }}</h4>
                            <div class="card-header-action">
                                <span class="badge badge-{{ $reminder->is_active ? 'success' : 'secondary' }} badge-lg">
                                    {{ $reminder->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Description</h6>
                                    <div class="bg-light p-3 rounded mb-3">
                                        {!! nl2br(e($reminder->description)) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Schedule Details</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Day of Month:</strong></td>
                                            <td>{{ $reminder->day_with_suffix }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Time:</strong></td>
                                            <td>{{ $reminder->formatted_time }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Next Reminder:</strong></td>
                                            <td>
                                                @if($reminder->is_active)
                                                    <span class="text-primary">
                                                        {{ $reminder->next_trigger_at->format('F j, Y') }}<br>
                                                        <small>{{ $reminder->next_trigger_at->format('g:i A') }}</small>
                                                    </span>
                                                @else
                                                    <span class="text-muted">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $reminder->created_at->format('M j, Y g:i A') }}</td>
                                        </tr>
                                        @if($reminder->updated_at != $reminder->created_at)
                                        <tr>
                                            <td><strong>Last Updated:</strong></td>
                                            <td>{{ $reminder->updated_at->format('M j, Y g:i A') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            @if($reminder->is_active)
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Next Reminder Schedule</h6>
                                    <p class="mb-0">
                                        Your next reminder will be sent on 
                                        <strong>{{ $reminder->next_trigger_at->format('F j, Y') }}</strong> 
                                        at <strong>{{ $reminder->next_trigger_at->format('g:i A') }}</strong>.
                                        @if($reminder->next_trigger_at->isToday())
                                            <span class="badge badge-warning ml-2">Today</span>
                                        @elseif($reminder->next_trigger_at->isTomorrow())
                                            <span class="badge badge-info ml-2">Tomorrow</span>
                                        @else
                                            <span class="badge badge-light ml-2">{{ $reminder->next_trigger_at->diffForHumans() }}</span>
                                        @endif
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-pause-circle"></i> Reminder Inactive</h6>
                                    <p class="mb-0">
                                        This reminder is currently inactive and will not send any notifications. 
                                        You can activate it using the actions panel.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Actions</h4>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('reminders.edit', $reminder) }}" class="btn btn-warning btn-block mb-2">
                                <i class="fas fa-edit"></i> Edit Reminder
                            </a>
                            
                            <form action="{{ route('reminders.test', $reminder) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-paper-plane"></i> Send Test Email
                                </button>
                            </form>
                            
                            <form action="{{ route('reminders.toggle-status', $reminder) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $reminder->is_active ? 'secondary' : 'success' }} btn-block">
                                    <i class="fas {{ $reminder->is_active ? 'fa-pause' : 'fa-play' }}"></i> 
                                    {{ $reminder->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            
                            <hr>
                            
                            <button type="button" class="btn btn-danger btn-block delete-btn"
                                data-id="{{ $reminder->id }}"
                                data-title="{{ $reminder->title }}">
                                <i class="fas fa-trash"></i> Delete Reminder
                            </button>
                            <form id="delete-form-{{ $reminder->id }}" action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            
                            <a href="{{ route('reminders.index') }}" class="btn btn-light btn-block mt-3">
                                <i class="fas fa-arrow-left"></i> Back to Reminders
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>How It Works</h4>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-point timeline-point-primary"></div>
                                    <div class="timeline-content">
                                        <h6>Email Sent</h6>
                                        <p class="text-muted mb-0">On the {{ $reminder->day_with_suffix }} at {{ $reminder->formatted_time }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-point timeline-point-success"></div>
                                    <div class="timeline-content">
                                        <h6>Auto-Scheduled</h6>
                                        <p class="text-muted mb-0">Next month's reminder is automatically scheduled</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-point timeline-point-info"></div>
                                    <div class="timeline-content">
                                        <h6>Repeats Monthly</h6>
                                        <p class="text-muted mb-0">Process continues every month</p>
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
            text: `Are you sure you want to delete "${title}"? This action cannot be undone.`,
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