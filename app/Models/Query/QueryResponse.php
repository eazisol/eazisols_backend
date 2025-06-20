<?php

namespace App\Models\Query;

use App\Models\Query;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'query_id',
        'user_id',
        'message',
        'is_admin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Get the query that owns the response.
     */
    public function parentQuery()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }

    /**
     * Get the user that created the response.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 