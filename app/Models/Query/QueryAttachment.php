<?php

namespace App\Models\Query;

use App\Models\Query;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'query_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    /**
     * Get the query that owns the attachment.
     */
    public function query()
    {
        return $this->belongsTo(Query::class);
    }
} 