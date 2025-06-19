<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliedJob extends Model
{
    use HasFactory;

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
        'status',
        // Add more fields as needed
    ];

    /**
     * Get the career that this application is for.
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }
} 