<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all attendance records for the user.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all leave requests for the user.
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get the attendance record for the specified date.
     */
    public function getAttendanceForDate($date)
    {
        return $this->attendances()->where('date', $date)->first();
    }

    /**
     * Check if the user is on leave for the specified date.
     */
    public function isOnLeaveForDate($date)
    {
        return $this->leaves()
            ->where('status', Leave::STATUS_APPROVED)
            ->where(function($query) use ($date) {
                $query->where(function($q) use ($date) {
                    $q->where('start_date', '<=', $date)
                      ->where('end_date', '>=', $date);
                });
            })->exists();
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function hasPermission($key)
    {
        return $this->role && $this->role->permissions->contains('key', $key);
    }
    public function empPersonalDetail()
    {
        return $this->hasOne(EmpPersonalDetail::class);
    }
    public function emergencyContacts()
    {
        return $this->hasMany(EmpEmergencyContact::class);
    }
    public function jobInformation()
    {
        return $this->hasOne(EmpJobInformation::class);
    }



}
