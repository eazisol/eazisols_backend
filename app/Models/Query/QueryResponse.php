<?php

namespace App\Models\Query;

use App\Models\Query;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'query_id',
        'user_id',
        'response',
        'is_internal',
        'sent_at',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    protected $dates = [
        'sent_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the query that owns the response.
     */
    public function query()
    {
        return $this->belongsTo(Query::class);
    }

    /**
     * Get the user that created the response.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 