<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
     // If needed, specify fillable fields
    protected $fillable = ['name'];

    // Each department can have multiple designations
    public function designations()
    {
        return $this->hasMany(Designation::class);
    }
}

