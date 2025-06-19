<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppliedJob extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'career_id',
        'name',
        'email',
        'phone',
        'resume',
        'cover_letter',
        'current_company',
        'current_position',
        'experience_years',
        'expected_salary',
        'available_from',
        'skills',
        'education',
        'certifications',
        'portfolio_url',
        'linkedin_url',
        'github_url',
        'additional_info',
        'status',
        'admin_notes',
        'reviewed_at',
        // Add more fields as needed
    ];

    protected $dates = [
        'available_from',
        'reviewed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the career that this application is for.
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }
} 