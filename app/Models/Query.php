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
        'type',
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
     * Query types
     */
    const TYPE_CONTACT = 'contact';
    const TYPE_COST_CALCULATOR = 'cost_calculator';

    /**
     * Status types
     */
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

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

    /**
     * Scope a query to only include contact queries.
     */
    public function scopeContact($query)
    {
        return $query->where('type', self::TYPE_CONTACT);
    }

    /**
     * Scope a query to only include cost calculator queries.
     */
    public function scopeCostCalculator($query)
    {
        return $query->where('type', self::TYPE_COST_CALCULATOR);
    }

    /**
     * Check if query is a contact type.
     */
    public function isContact()
    {
        return $this->type === self::TYPE_CONTACT;
    }

    /**
     * Check if query is a cost calculator type.
     */
    public function isCostCalculator()
    {
        return $this->type === self::TYPE_COST_CALCULATOR;
    }
} 