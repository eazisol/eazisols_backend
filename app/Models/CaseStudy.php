<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'client_name',
        'industry',
        'challenge',
        'solution',
        'results',
        'featured_image',
        'status',
        'published_at',
        // Add more fields as needed
    ];
} 