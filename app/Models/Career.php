<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
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
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'location',
        'work_type',           // Full-time, Part-time, Contract, Remote, etc.
        // 'department',
        'workplace_type',   // new
        'department',
        'experience_level',
        'education',
        'salary_range',
        'application_deadline',
        'status',         // active, inactive, filled
        'featured',       // boolean to highlight top positions
        'vacancy_count',  // number of positions available
        'category'        // career category
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'application_deadline' => 'date',
        'featured' => 'boolean',
        'vacancy_count' => 'integer',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get all applications for this career.
     */
    public function applications()
    {
        return $this->hasMany(AppliedJob::class);
    }

    /**
     * Scope a query to only include active careers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured careers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to filter by type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('work_type', $type);
    }

    /**
     * Scope a query to filter by location.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $location
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInLocation($query, $location)
    {
        return $query->where('location', 'LIKE', "%{$location}%");
    }

    /**
     * Scope a query to filter by department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $department
     * @return \Illuminate\Database\Eloquent\Builder
     */
    /*
    public function scopeInDepartment($query, $department)
    {
        return $query->where('department', $department);
    }
    */
} 