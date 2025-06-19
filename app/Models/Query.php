<?php

namespace App\Models;

use App\Models\Query\QueryAttachment;
use App\Models\Query\QueryResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Query extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'subject',
        'message',
        'source',
        'status',
        'admin_notes',
        'assigned_to',
        'resolved_at',
    ];

    protected $dates = [
        'resolved_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the user that the query is assigned to.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the responses for the query.
     */
    public function responses()
    {
        return $this->hasMany(QueryResponse::class);
    }

    /**
     * Get the attachments for the query.
     */
    public function attachments()
    {
        return $this->hasMany(QueryAttachment::class);
    }

    /**
     * Scope a query to only include unresolved queries.
     */
    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    /**
     * Scope a query to only include resolved queries.
     */
    public function scopeResolved($query)
    {
        return $query->whereNotNull('resolved_at');
    }
} 