<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'date',
        'status',
        'check_in_time',
        'check_out_time',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LEAVE = 'leave';
    const STATUS_HALF_DAY = 'half-day';
    const STATUS_LATE = 'late';
    const STATUS_PUBLIC_HOLIDAY = 'public_holiday';

    /**
     * Get the user that owns the attendance record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include present attendance.
     */
    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    /**
     * Scope a query to only include absent attendance.
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', self::STATUS_ABSENT);
    }

    /**
     * Scope a query to only include leave attendance.
     */
    public function scopeOnLeave($query)
    {
        return $query->where('status', self::STATUS_LEAVE);
    }

    /**
     * Scope a query to only include half-day attendance.
     */
    public function scopeHalfDay($query)
    {
        return $query->where('status', self::STATUS_HALF_DAY);
    }

    /**
     * Scope a query to only include late attendance.
     */
    public function scopeLate($query)
    {
        return $query->where('status', self::STATUS_LATE);
    }

    /**
     * Scope a query to only include public holiday attendance.
     */
    public function scopePublicHoliday($query)
    {
        return $query->where('status', self::STATUS_PUBLIC_HOLIDAY);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
} 