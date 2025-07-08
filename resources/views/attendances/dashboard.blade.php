@extends('layouts.main')

@section('title')
    My Attendance
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>My Attendance</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">My Attendance</div>
        </div>
    </div>

    <div class="section-body">
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

        <!-- Today's Attendance Card -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Today's Attendance</h4>
                        <div class="card-header-action">
                            <span class="badge badge-primary">{{ $today->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($todayAttendance)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mr-2">Status:</h6>
                                    @switch($todayAttendance->status)
                                        @case('present')
                                            <span class="badge badge-success">Present</span>
                                            @break
                                        @case('absent')
                                            <span class="badge badge-danger">Absent</span>
                                            @break
                                        @case('leave')
                                            <span class="badge badge-info">Leave</span>
                                            @break
                                        @case('half-day')
                                            <span class="badge badge-warning">Half Day</span>
                                            @break
                                        @case('late')
                                            <span class="badge badge-warning">Late</span>
                                            @break
                                    @endswitch
                                </div>
                                
                                @if($todayAttendance->check_in_time)
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mr-2">Check In:</h6>
                                    <span>{{ $todayAttendance->check_in_time->setTimezone(config('app.timezone'))->format('h:i A') }}</span>
                                </div>
                                @endif
                                
                                @if($todayAttendance->check_out_time)
                                <div class="d-flex align-items-center">
                                    <h6 class="mr-2">Check Out:</h6>
                                    <span>{{ $todayAttendance->check_out_time->setTimezone(config('app.timezone'))->format('h:i A') }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="buttons">
                                @if(!$todayAttendance->check_in_time)
                                <form action="{{ route('attendances.check-in') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Check In</button>
                                </form>
                                @elseif(!$todayAttendance->check_out_time)
                                <form action="{{ route('attendances.check-out') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info">Check Out</button>
                                </form>
                                @else
                                <button class="btn btn-secondary" disabled>Checked Out</button>
                                @endif
                            </div>
                        @else
                            <p>No attendance record for today.</p>
                            <form action="{{ route('attendances.check-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Check In</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Monthly Statistics</h4>
                        <div class="card-header-action">
                            <span class="badge badge-primary">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <div class="text-center">
                                    <div class="font-weight-bold text-success mb-1">Present</div>
                                    <div class="h3">{{ $stats['present'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="text-center">
                                    <div class="font-weight-bold text-danger mb-1">Absent</div>
                                    <div class="h3">{{ $stats['absent'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="text-center">
                                    <div class="font-weight-bold text-info mb-1">Leave</div>
                                    <div class="h3">{{ $stats['leave'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6 mb-3">
                                <div class="text-center">
                                    <div class="font-weight-bold text-warning mb-1">Half Day</div>
                                    <div class="h3">{{ $stats['half_day'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6 mb-3">
                                <div class="text-center">
                                    <div class="font-weight-bold text-warning mb-1">Late</div>
                                    <div class="h3">{{ $stats['late'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Attendance</h4>
                        <div class="card-header-action">
                            <a href="{{ route('leaves.create') }}" class="btn btn-primary mr-2">Apply for Leave</a>
                            <a href="{{ route('attendances.index') }}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Working Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentAttendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d M Y') }}</td>
                                            <td>
                                                @switch($attendance->status)
                                                    @case('present')
                                                        <span class="badge badge-success">Present</span>
                                                        @break
                                                    @case('absent')
                                                        <span class="badge badge-danger">Absent</span>
                                                        @break
                                                    @case('leave')
                                                        <span class="badge badge-info">Leave</span>
                                                        @break
                                                    @case('half-day')
                                                        <span class="badge badge-warning">Half Day</span>
                                                        @break
                                                    @case('late')
                                                        <span class="badge badge-warning">Late</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                {{ $attendance->check_in_time ? $attendance->check_in_time->setTimezone(config('app.timezone'))->format('h:i A') : 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $attendance->check_out_time ? $attendance->check_out_time->setTimezone(config('app.timezone'))->format('h:i A') : 'N/A' }}
                                            </td>
                                            <td>
                                                @if($attendance->check_in_time && $attendance->check_out_time)
                                                    @php
                                                        $hours = $attendance->check_in_time->diffInHours($attendance->check_out_time);
                                                        $minutes = $attendance->check_in_time->diffInMinutes($attendance->check_out_time) % 60;
                                                        echo $hours . 'h ' . $minutes . 'm';
                                                    @endphp
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No attendance records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(count($pendingLeaves) > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Pending Leave Requests</h4>
                        <div class="card-header-action">
                            <a href="{{ route('leaves.index') }}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Duration</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingLeaves as $leave)
                                        <tr>
                                            <td>{{ $leave->start_date->format('d M Y') }}</td>
                                            <td>{{ $leave->end_date->format('d M Y') }}</td>
                                            <td>{{ $leave->duration }} day(s)</td>
                                            <td>{{ \Illuminate\Support\Str::limit($leave->reason, 50) }}</td>
                                            <td>
                                                <span class="badge badge-warning">Pending</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection 