<?php

namespace App\Models\Blog;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the blogs for the category.
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_blog_category', 'blog_category_id', 'blog_id');
    }
} 