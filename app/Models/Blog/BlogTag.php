<?php

namespace App\Models\Blog;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the blogs for the tag.
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_blog_tag', 'blog_tag_id', 'blog_id');
    }
} 