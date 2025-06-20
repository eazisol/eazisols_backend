<?php

namespace App\Models\Query;

use App\Models\Query;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'query_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    /**
     * Get the query that owns the attachment.
     */
    public function parentQuery()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }
} 