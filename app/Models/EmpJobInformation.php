<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpJobInformation extends Model
{
    use HasFactory;

    protected $table = 'emp_job_information';

    protected $fillable = [
        'user_id',
        'department_id',
        'designation_id',
        'work_type',
        'joining_date',
        'probation_end_date',
        'reporting_manager_id',
        'reporting_teamlead_id',
        'work_location',
    ];

    // Employee this job info belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Manager (also a user)
    public function manager()
    {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }

    // Team Lead (also a user)
    public function teamLead()
    {
        return $this->belongsTo(User::class, 'reporting_teamlead_id');
    }
}
