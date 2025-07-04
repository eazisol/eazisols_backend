@extends('layouts.main')

@section('title')
    Attendance Register
@endsection

@section('content')
<!-- Debug information - will only show to admins -->
@if(auth()->check() && auth()->user()->id === 1)
<div class="container mb-3">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Debug Information</h5>
        </div>
        <div class="card-body">
            <p><strong>Total Attendance Records:</strong> {{ $allAttendances->count() }}</p>
            <p><strong>Raw Attendance Count:</strong> {{ count($rawAttendances) }}</p>
            
            <p><strong>All Attendance Records:</strong></p>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Date (Original)</th>
                        <th>Date String</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allAttendances as $att)
                    <tr>
                        <td>{{ $att->id }}</td>
                        <td>{{ $att->user_id }} ({{ optional(\App\Models\User::find($att->user_id))->name }})</td>
                        <td><code>{{ $att->date }}</code></td>
                        <td><code>{{ $att->date_string }}</code></td>
                        <td><code>{{ $att->status }}</code></td>
                        <td>{{ $att->check_in_time }}</td>
                        <td>{{ $att->check_out_time }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <hr>
            
            <p><strong>Calendar Dates:</strong></p>
            <ul>
                @foreach($calendar as $date => $details)
                <li><code>{{ $date }}</code></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<section class="section">
    <div class="section-header">
        <h1>Attendance Register</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Attendance Register</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Monthly Attendance - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</h4>
                        <div class="card-header-action">
                            <form class="form-inline">
                                <div class="form-group mr-2">
                                    <select class="form-control" name="month" onchange="this.form.submit()">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="year" onchange="this.form.submit()">
                                        @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#markAttendanceModal">
                                <i class="fas fa-user-check"></i> Mark Attendance
                            </button>
                            <button class="btn btn-info" data-toggle="modal" data-target="#importAttendanceModal">
                                <i class="fas fa-file-import"></i> Import Attendance
                            </button>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>×</span></button>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>×</span></button>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        <!-- Attendance Legend -->
                        <div class="attendance-legend mb-4">
                            <div class="d-flex flex-wrap">
                                <div class="legend-item mr-3 mb-2">
                                    <span class="legend-color" style="background-color: #28a745;"></span>
                                    <span class="legend-text">Present</span>
                                </div>
                                <div class="legend-item mr-3 mb-2">
                                    <span class="legend-color" style="background-color: #dc3545;"></span>
                                    <span class="legend-text">Absent</span>
                                </div>
                                <div class="legend-item mr-3 mb-2">
                                    <span class="legend-color" style="background-color: #17a2b8;"></span>
                                    <span class="legend-text">Leave</span>
                                </div>
                                <div class="legend-item mr-3 mb-2">
                                    <span class="legend-color" style="background-color: #ffc107;"></span>
                                    <span class="legend-text">Half Day</span>
                                </div>
                                <div class="legend-item mr-3 mb-2">
                                    <span class="legend-color" style="background-color: #fd7e14;"></span>
                                    <span class="legend-text">Late</span>
                                </div>
                                <div class="legend-item mr-3 mb-2">
                                    <span class="legend-color" style="background-color: #e9ecef;"></span>
                                    <span class="legend-text">Weekend</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        @foreach($calendar as $date => $details)
                                            <th class="{{ $details['isWeekend'] ? 'bg-light' : '' }}" style="min-width: 40px; text-align: center;">
                                                <small>{{ \Carbon\Carbon::parse($date)->format('d') }}</small>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            @foreach($calendar as $date => $details)
                                                @php
                                                    // Find attendance for this user and date - Fixed version
                                                    $attendance = null;
                                                    foreach($allAttendances as $record) {
                                                        if ($record->user_id == $user->id && $record->date_string == $date) {
                                                            $attendance = $record;
                                                            break;
                                                        }
                                                    }
                                                    
                                                    $isOnLeave = $leaves->filter(function($leave) use ($date, $user) {
                                                        return $leave->user_id == $user->id && 
                                                            $leave->start_date->lte(\Carbon\Carbon::parse($date)) && 
                                                            $leave->end_date->gte(\Carbon\Carbon::parse($date));
                                                    })->isNotEmpty();
                                                    
                                                    $bgColor = '';
                                                    $tooltip = '';
                                                    $icon = '';
                                                    
                                                    if ($details['isWeekend']) {
                                                        $bgColor = 'bg-light';
                                                    } elseif ($attendance) {
                                                        $status = is_string($attendance->status) ? strtolower(trim($attendance->status)) : '';
                                                        
                                                        switch($status) {
                                                            case 'present':
                                                                $bgColor = 'bg-success';
                                                                $tooltip = 'Present';
                                                                $icon = 'fa-check';
                                                                break;
                                                            case 'absent':
                                                                $bgColor = 'bg-danger';
                                                                $tooltip = 'Absent';
                                                                $icon = 'fa-times';
                                                                break;
                                                            case 'leave':
                                                                $bgColor = 'bg-info';
                                                                $tooltip = 'Leave';
                                                                $icon = 'fa-calendar-minus';
                                                                break;
                                                            case 'half-day':
                                                                $bgColor = 'bg-warning';
                                                                $tooltip = 'Half Day';
                                                                $icon = 'fa-adjust';
                                                                break;
                                                            case 'late':
                                                                $bgColor = 'bg-orange';
                                                                $tooltip = 'Late';
                                                                $icon = 'fa-clock';
                                                                break;
                                                            default:
                                                                $bgColor = 'bg-secondary';
                                                                $tooltip = 'Unknown: ' . $attendance->status;
                                                                $icon = 'fa-question';
                                                                break;
                                                        }
                                                        
                                                        if ($attendance->check_in_time) {
                                                            $tooltip .= ' (In: ' . Carbon\Carbon::parse($attendance->check_in_time)->setTimezone(config('app.timezone'))->format('h:i A') . ')';
                                                        }
                                                        
                                                        if ($attendance->check_out_time) {
                                                            $tooltip .= ' (Out: ' . Carbon\Carbon::parse($attendance->check_out_time)->setTimezone(config('app.timezone'))->format('h:i A') . ')';
                                                        }
                                                    } elseif ($isOnLeave) {
                                                        $bgColor = 'bg-info';
                                                        $tooltip = 'On Leave';
                                                        $icon = 'fa-calendar-minus';
                                                    }
                                                @endphp
                                                <td class="{{ $bgColor }}" data-toggle="tooltip" title="{{ $tooltip }}" style="text-align: center; height: 40px; padding: 0;">
                                                    @if($attendance || $isOnLeave)
                                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas {{ $icon }} text-white"></i>
                                                        </div>
                                                        @if(auth()->check() && auth()->user()->id === 1)
                                                            <!-- Admin-only debug -->
                                                            <span style="display:none;">
                                                                @if($attendance) Status: {{ $attendance->status }} @endif
                                                                @if($isOnLeave) Leave @endif
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mark Attendance Modal -->
<div class="modal fade" id="markAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="markAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAttendanceModalLabel">Mark Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('attendances.mark') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Employee</label>
                        <select class="form-control" name="user_id" required>
                            <option value="">Select Employee</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" required>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="leave">Leave</option>
                            <option value="half-day">Half Day</option>
                            <option value="late">Late</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Check In Time</label>
                        <input type="time" class="form-control" name="check_in_time">
                    </div>
                    <div class="form-group">
                        <label>Check Out Time</label>
                        <input type="time" class="form-control" name="check_out_time">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Mark Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Attendance Modal -->
<div class="modal fade" id="importAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="importAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importAttendanceModalLabel">Import Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('attendances.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>CSV File</label>
                        <input type="file" class="form-control" name="file" required>
                        <small class="form-text text-muted">
                            File should contain the following columns: email, date, status, check_in_time (optional), check_out_time (optional)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.legend-item {
    display: flex;
    align-items: center;
}
.legend-color {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 5px;
    border-radius: 3px;
}
.bg-orange {
    background-color: #fd7e14;
    color: white;
}
</style>

@endsection

@section('scripts')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
