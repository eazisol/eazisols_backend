<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $table = 'interviews'; // Table name

    protected $fillable = [
        // Candidate details
        'name',
        'email',
        'phone',
        'qualification',
        'year',
        'age',
        'home_town',
        'current_location',

        // Interview details
        'date_of_interview',
        'interview_time',
        'position_applied',
        'interview_type',
        'name_of_interviewer',
        'remarks',
        'technical_remarks',
        'technical_interview_conducted_by',

        // Job-related info
        'job_status',
        'marital_status',
        'technical_skills',
        'reference',
        'last_company_name',
        'employee_count',
        'total_experience',
        'last_job_position',
        'relevant_experience',
        'last_current_salary',
        'notice_period',
        'expected_salary',
        'negotiable',
        'immediate_joining',
        'reason_for_leaving',
        'other_benefits',

        // Miscellaneous
        'communication_skills',
        'health_condition',
        'currently_studying',
        'interviewed_previously',
    ];

    // If you want to automatically cast some fields
    protected $casts = [
        'date_of_interview' => 'date',
        'negotiable' => 'boolean',
        'immediate_joining' => 'boolean',
        'currently_studying' => 'boolean',
        'interviewed_previously' => 'boolean',
        'total_experience' => 'decimal:2',
        'relevant_experience' => 'decimal:2',
        'last_current_salary' => 'decimal:2',
        'expected_salary' => 'decimal:2',
    ];
}
