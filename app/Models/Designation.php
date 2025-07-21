<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department_id'];

    // Each designation belongs to a department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
