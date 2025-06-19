<?php

namespace App\Models\CaseStudy;

use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the case studies for the category.
     */
    public function caseStudies()
    {
        return $this->belongsToMany(CaseStudy::class, 'case_study_category', 'case_study_category_id', 'case_study_id');
    }
} 