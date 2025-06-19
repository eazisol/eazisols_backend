<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
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
        'excerpt',
        'content',
        'featured_image',
        'author_id',
        'category',
        'tags',
        'read_time',
        'status',
        'featured',
        'allow_comments',
        'view_count',
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
        'featured' => 'boolean',
        'allow_comments' => 'boolean',
        'view_count' => 'integer',
        'read_time' => 'integer',
    ];

    /**
     * Get the author of the blog post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the categories for the blog post.
     */
    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_blog_category', 'blog_id', 'blog_category_id');
    }

    /**
     * Get the tags for the blog post.
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_blog_tag', 'blog_id', 'blog_tag_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
} 