<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Default to current month if no date is provided
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        // Get the first and last day of the month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Get all users
        $users = User::all();
        
        // Get all attendance records for the month - but don't group them
        $allAttendances = Attendance::whereBetween('date', [
                $startDate->format('Y-m-d'), 
                $endDate->format('Y-m-d')
            ])
            ->orderBy('date', 'asc')
            ->get();
            
        // Convert date attributes to strings for easier comparison in the view
        $allAttendances = $allAttendances->map(function($attendance) {
            // Make sure date is accessible as a string for comparison in the view
            if ($attendance->date instanceof \Carbon\Carbon) {
                $attendance->date_string = $attendance->date->format('Y-m-d');
            } else {
                $attendance->date_string = $attendance->date;
            }
            return $attendance;
        });
        
        // Add raw query for debugging
        $rawAttendances = DB::select('SELECT * FROM attendances');
        
        // Log the attendance data
        \Log::info('Attendance data for calendar', [
            'count' => $allAttendances->count(),
            'raw_count' => count($rawAttendances),
            'raw_data' => $rawAttendances,
            'first_few' => $allAttendances->take(3)->map(function($att) {
                return [
                    'id' => $att->id,
                    'user_id' => $att->user_id,
                    'date' => $att->date instanceof \Carbon\Carbon ? $att->date->format('Y-m-d') : $att->date,
                    'status' => $att->status
                ];
            })
        ]);
        
        // Get all approved leaves for the month
        $leaves = Leave::where('status', Leave::STATUS_APPROVED)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();
            
        // Debug the data types
        // dd($users->first()->id, gettype($users->first()->id), $attendances->keys()->first(), gettype($attendances->keys()->first()));

        // Create calendar data
        $calendar = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $calendar[$currentDate->format('Y-m-d')] = [
                'date' => $currentDate->copy(),
                'isWeekend' => $currentDate->isWeekend(),
            ];
            $currentDate->addDay();
        }
        
        return view('attendances.index', compact('users', 'allAttendances', 'rawAttendances', 'leaves', 'calendar', 'month', 'year'));
    }
    
    /**
     * Display the current user's attendance dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        
        // Get today's attendance
        $todayAttendance = $user->getAttendanceForDate($today);
        
        // Get attendance stats for current month
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $monthAttendances = $user->attendances()
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
        
        $stats = [
            'present' => $monthAttendances->where('status', Attendance::STATUS_PRESENT)->count(),
            'absent' => $monthAttendances->where('status', Attendance::STATUS_ABSENT)->count(),
            'leave' => $monthAttendances->where('status', Attendance::STATUS_LEAVE)->count(),
            'half_day' => $monthAttendances->where('status', Attendance::STATUS_HALF_DAY)->count(),
            'late' => $monthAttendances->where('status', Attendance::STATUS_LATE)->count(),
        ];
        
        // Get recent attendance records
        $recentAttendances = $user->attendances()
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();
        
        // Get pending leave requests
        $pendingLeaves = $user->leaves()
            ->where('status', Leave::STATUS_PENDING)
            ->orderBy('start_date', 'asc')
            ->get();
        
        // Get leave history (approved/rejected)
        $leaveHistory = $user->leaves()
            ->whereIn('status', [Leave::STATUS_APPROVED, Leave::STATUS_REJECTED])
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('attendances.dashboard', compact(
            'user', 'today', 'todayAttendance', 'stats',
            'recentAttendances', 'pendingLeaves', 'leaveHistory'
        ));
    }
    
    /**
     * Check in the user for today.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();
        
        // Log current time for debugging
        \Log::info('Check In', [
            'server_time' => $now->format('Y-m-d H:i:s'),
            'timezone' => config('app.timezone'),
        ]);
        
        // Check if already checked in
        $attendance = $user->getAttendanceForDate($today);
        
        if ($attendance) {
            if ($attendance->check_in_time) {
                return redirect()
                    ->route('attendances.dashboard')
                    ->with('error', 'You have already checked in today.');
            }
        } else {
            // Create new attendance record
            $attendance = new Attendance([
                'user_id' => $user->id,
                'date' => $today,
                'status' => Attendance::STATUS_PRESENT,
            ]);
        }
        
        // Set check-in time
        $attendance->check_in_time = $now;
        
        // Check if the user is late (after 9:30 AM)
        $startTime = Carbon::createFromTime(9, 30, 0);
        if ($now->gt($startTime)) {
            $attendance->status = Attendance::STATUS_LATE;
        }
        
        $attendance->save();
        
        return redirect()
            ->route('attendances.dashboard')
            ->with('success', 'You have successfully checked in at ' . $now->format('h:i A'));
    }
    
    /**
     * Check out the user for today.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();
        
        // Log current time for debugging
        \Log::info('Check Out', [
            'server_time' => $now->format('Y-m-d H:i:s'),
            'timezone' => config('app.timezone'),
        ]);
        
        // Check if already checked in
        $attendance = $user->getAttendanceForDate($today);
        
        if (!$attendance || !$attendance->check_in_time) {
            return redirect()
                ->route('attendances.dashboard')
                ->with('error', 'You need to check in first.');
        }
        
        if ($attendance->check_out_time) {
            return redirect()
                ->route('attendances.dashboard')
                ->with('error', 'You have already checked out today.');
        }
        
        // Set check-out time
        $attendance->check_out_time = $now;
        $attendance->save();
        
        return redirect()
            ->route('attendances.dashboard')
            ->with('success', 'You have successfully checked out at ' . $now->format('h:i A'));
    }
    
    /**
     * Mark attendance for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,leave,half-day,late',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = User::findOrFail($request->user_id);
        $date = Carbon::parse($request->date)->format('Y-m-d');
        
        // Check if attendance already exists
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $date)
            ->first();
            
        if (!$attendance) {
            $attendance = new Attendance([
                'user_id' => $user->id,
                'date' => $date,
            ]);
        }
        
        // Ensure status is a recognized value
        $validStatuses = ['present', 'absent', 'leave', 'half-day', 'late'];
        $status = strtolower(trim($request->status));
        
        if (!in_array($status, $validStatuses)) {
            return redirect()
                ->back()
                ->with('error', 'Invalid status value: ' . $request->status);
        }
        
        // For debugging
        \Log::info('Marking attendance', [
            'user_id' => $user->id,
            'date' => $date,
            'status' => $status,
            'raw_status' => $request->status,
            'timezone' => config('app.timezone'),
        ]);
        
        $attendance->status = $status;
        
        // Handle check-in and check-out times
        if ($request->filled('check_in_time')) {
            // Create a Carbon instance in the application's timezone
            $checkInTime = Carbon::parse($date . ' ' . $request->check_in_time, config('app.timezone'));
            $attendance->check_in_time = $checkInTime;
            
            \Log::info('Check-in time set', [
                'input_time' => $request->check_in_time,
                'parsed_time' => $checkInTime->format('Y-m-d H:i:s'),
                'timezone' => $checkInTime->timezone->getName(),
            ]);
        }
        
        if ($request->filled('check_out_time')) {
            // Create a Carbon instance in the application's timezone
            $checkOutTime = Carbon::parse($date . ' ' . $request->check_out_time, config('app.timezone'));
            $attendance->check_out_time = $checkOutTime;
            
            \Log::info('Check-out time set', [
                'input_time' => $request->check_out_time,
                'parsed_time' => $checkOutTime->format('Y-m-d H:i:s'),
                'timezone' => $checkOutTime->timezone->getName(),
            ]);
        }
        
        $attendance->save();
        
        // For debugging
        \Log::info('Attendance saved', [
            'id' => $attendance->id,
            'status' => $attendance->status,
            'status_from_db' => Attendance::find($attendance->id)->status,
            'check_in' => $attendance->check_in_time ? $attendance->check_in_time->format('Y-m-d H:i:s') : null,
            'check_out' => $attendance->check_out_time ? $attendance->check_out_time->format('Y-m-d H:i:s') : null,
        ]);
        
        return redirect()
            ->back()
            ->with('success', 'Attendance marked successfully for ' . $user->name . ' on ' . Carbon::parse($date)->format('d M Y'));
    }
    
    /**
     * Import bulk attendance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);
        
        $requiredHeaders = ['email', 'date', 'status'];
        $missingHeaders = array_diff($requiredHeaders, $header);
        
        if (!empty($missingHeaders)) {
            return redirect()
                ->back()
                ->with('error', 'CSV file is missing required headers: ' . implode(', ', $missingHeaders));
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            $importCount = 0;
            $errors = [];
            
            foreach ($data as $row) {
                $rowData = array_combine($header, $row);
                
                $user = User::where('email', $rowData['email'])->first();
                
                if (!$user) {
                    $errors[] = 'User with email ' . $rowData['email'] . ' not found';
                    continue;
                }
                
                $date = Carbon::parse($rowData['date'])->format('Y-m-d');
                $status = $rowData['status'];
                
                if (!in_array($status, ['present', 'absent', 'leave', 'half-day', 'late'])) {
                    $errors[] = 'Invalid status "' . $status . '" for ' . $rowData['email'] . ' on ' . $date;
                    continue;
                }
                
                // Check if attendance already exists
                $attendance = Attendance::where('user_id', $user->id)
                    ->where('date', $date)
                    ->first();
                    
                if (!$attendance) {
                    $attendance = new Attendance([
                        'user_id' => $user->id,
                        'date' => $date,
                    ]);
                }
                
                $attendance->status = $status;
                
                // Handle check-in and check-out times if provided
                if (isset($rowData['check_in_time']) && !empty($rowData['check_in_time'])) {
                    $attendance->check_in_time = Carbon::parse($date . ' ' . $rowData['check_in_time']);
                }
                
                if (isset($rowData['check_out_time']) && !empty($rowData['check_out_time'])) {
                    $attendance->check_out_time = Carbon::parse($date . ' ' . $rowData['check_out_time']);
                }
                
                $attendance->save();
                $importCount++;
            }
            
            DB::commit();
            
            $message = 'Successfully imported ' . $importCount . ' attendance records';
            if (!empty($errors)) {
                $message .= ' with ' . count($errors) . ' errors';
            }
            
            return redirect()
                ->back()
                ->with('success', $message)
                ->with('import_errors', $errors);
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->back()
                ->with('error', 'Error importing attendance: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate attendance report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $userId = $request->user_id;
        
        $query = Attendance::whereBetween('date', [$startDate, $endDate])
            ->with('user');
            
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $attendances = $query->orderBy('date', 'asc')
            ->orderBy('user_id', 'asc')
            ->get();
            
        // Generate stats
        $stats = [
            'total_days' => $startDate->diffInDays($endDate) + 1,
            'present' => $attendances->where('status', Attendance::STATUS_PRESENT)->count(),
            'absent' => $attendances->where('status', Attendance::STATUS_ABSENT)->count(),
            'leave' => $attendances->where('status', Attendance::STATUS_LEAVE)->count(),
            'half_day' => $attendances->where('status', Attendance::STATUS_HALF_DAY)->count(),
            'late' => $attendances->where('status', Attendance::STATUS_LATE)->count(),
        ];
        
        // Group by user
        $attendancesByUser = $attendances->groupBy('user_id');
        
        return view('attendances.report', compact('attendances', 'attendancesByUser', 'stats', 'startDate', 'endDate', 'userId'));
    }
} 