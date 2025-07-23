@extends('layouts.main')

@section('title', 'Create New Reminder')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create New Reminder</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('reminders.index') }}">Reminders</a></div>
            <div class="breadcrumb-item">Create</div>
        </div>
    </div>

    <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Reminder Details</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reminders.store') }}" method="POST">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="title">Reminder Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
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
                                              required>{{ old('description') }}</textarea>
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
                                                    <option value="{{ $i }}" {{ old('day_of_month') == $i ? 'selected' : '' }}>
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
                                                   value="{{ old('time_of_day', '09:00') }}" 
                                                   required>
                                            @error('time_of_day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> How it works:</h6>
                                    <ul class="mb-0">
                                        <li>You'll receive an email reminder on the specified day and time every month</li>
                                        <li>After sending, the system automatically schedules the next month's reminder</li>
                                        <li>You can test, edit, or deactivate reminders anytime</li>
                                    </ul>
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Reminder
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
                            <h4>Examples</h4>
                        </div>
                        <div class="card-body">
                            <h6>Common HR Reminders:</h6>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0">
                                    <strong>Salary Payment</strong><br>
                                    <small class="text-muted">25th of every month at 9:00 AM</small>
                                </div>
                                <div class="list-group-item px-0">
                                    <strong>Monthly Reports</strong><br>
                                    <small class="text-muted">1st of every month at 10:00 AM</small>
                                </div>
                                <div class="list-group-item px-0">
                                    <strong>Compliance Submission</strong><br>
                                    <small class="text-muted">15th of every month at 2:00 PM</small>
                                </div>
                                <div class="list-group-item px-0">
                                    <strong>Utility Bills</strong><br>
                                    <small class="text-muted">5th of every month at 11:00 AM</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
@endsection