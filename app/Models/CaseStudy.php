<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaseStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'client_name',
        'category',
        'category_id',
        'short_summary',
        'description',
        'thumbnail',
        'images',
        'project_url',
        'status',
        'start_date',
        'end_date',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Automatically generate slug on create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($caseStudy) {
            if (empty($caseStudy->slug)) {
                $caseStudy->slug = Str::slug($caseStudy->title);
            }
        });

        static::updating(function ($caseStudy) {
            if (empty($caseStudy->slug)) {
                $caseStudy->slug = Str::slug($caseStudy->title);
            }
        });
    }
    
    /**
     * Get the category that the case study belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
