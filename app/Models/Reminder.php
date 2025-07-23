<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'day_of_month',
        'time_of_day',
        'next_trigger_at',
        'notified',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'next_trigger_at' => 'datetime',
        'time_of_day' => 'datetime:H:i:s',
        'notified' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the reminder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate the next trigger datetime based on day_of_month and time_of_day.
     *
     * @param Carbon|null $fromDate
     * @return Carbon
     */
    public function calculateNextTrigger($fromDate = null)
    {
        $fromDate = $fromDate ?: Carbon::now();
        
        // Create a carbon instance for the target day and time in current month
        $targetDate = Carbon::create(
            $fromDate->year,
            $fromDate->month,
            min($this->day_of_month, $fromDate->daysInMonth),
            Carbon::parse($this->time_of_day)->hour,
            Carbon::parse($this->time_of_day)->minute,
            0
        );

        // If the target date has already passed this month, move to next month
        if ($targetDate->lte($fromDate)) {
            $targetDate->addMonth();
            
            // Adjust day if next month has fewer days
            $targetDate->day = min($this->day_of_month, $targetDate->daysInMonth);
        }

        return $targetDate;
    }

    /**
     * Update the next trigger date and reset notification status.
     */
    public function scheduleNext()
    {
        $this->next_trigger_at = $this->calculateNextTrigger();
        $this->notified = false;
        $this->save();
    }

    /**
     * Mark reminder as notified and schedule next occurrence.
     */
    public function markAsNotified()
    {
        $this->notified = true;
        $this->save();
        
        // Schedule next occurrence
        $this->scheduleNext();
    }

    /**
     * Scope to get due reminders.
     */
    public function scopeDue($query)
    {
        return $query->where('next_trigger_at', '<=', Carbon::now())
                    ->where('notified', false)
                    ->where('is_active', true);
    }

    /**
     * Scope to get active reminders.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted time for display.
     */
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->time_of_day)->format('g:i A');
    }

    /**
     * Get day suffix (1st, 2nd, 3rd, etc.).
     */
    public function getDayWithSuffixAttribute()
    {
        $day = $this->day_of_month;
        $suffix = 'th';
        
        if ($day % 10 == 1 && $day != 11) {
            $suffix = 'st';
        } elseif ($day % 10 == 2 && $day != 12) {
            $suffix = 'nd';
        } elseif ($day % 10 == 3 && $day != 13) {
            $suffix = 'rd';
        }
        
        return $day . $suffix;
    }
}