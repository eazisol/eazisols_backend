@extends('layouts.main')

@section('title')
    Apply for Leave
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Apply for Leave</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('attendances.dashboard') }}">My Attendance</a></div>
            <div class="breadcrumb-item">Apply for Leave</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Leave Request Form</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>×</span></button>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('leaves.store') }}" method="POST">
                            @csrf
                            
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Start Date</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">End Date</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        End date must be equal to or after the start date.
                                    </small>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Reason for Leave</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" rows="5" required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Please provide a detailed reason for your leave request.
                                    </small>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
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
    $(function() {
        // Update min value of end_date when start_date changes
        $('input[name="start_date"]').on('change', function() {
            $('input[name="end_date"]').attr('min', $(this).val());
            
            // If end_date is earlier than start_date, set it to start_date
            if ($('input[name="end_date"]').val() < $(this).val()) {
                $('input[name="end_date"]').val($(this).val());
            }
        });
    });
</script>
@endsection 