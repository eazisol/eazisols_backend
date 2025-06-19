<?php

namespace App\Models;

use App\Models\CaseStudy\CaseStudyCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseStudy extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'client_name',
        'client_industry',
        'client_location',
        'project_duration',
        'challenge',
        'solution',
        'results',
        'testimonial',
        'featured_image',
        'client_logo',
        'gallery_images',
        'video_url',
        'technologies_used',
        'status',
        'category',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'gallery_images' => 'array',
    ];

    /**
     * Get the categories for the case study.
     */
    public function categories()
    {
        return $this->belongsToMany(CaseStudyCategory::class, 'case_study_category', 'case_study_id', 'case_study_category_id');
    }

    /**
     * Scope a query to only include published case studies.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }
} 