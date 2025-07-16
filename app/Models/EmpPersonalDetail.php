<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPersonalDetail extends Model
{
    use HasFactory;

    protected $table = 'emp_personal_details';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'gender',
        'date_of_birth',
        'current_address',
        'permanent_address',
        'city',
        'state',
        'country',
    ];
    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
