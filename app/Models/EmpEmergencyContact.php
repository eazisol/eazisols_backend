<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpEmergencyContact extends Model
{
    use HasFactory;

    protected $table = 'emp_emergency_contacts';

    protected $fillable = [
        'user_id',
        'contact_name',
        'relationship',
        'phone_number',
        'alternate_phone',
    ];

    // Relationship: Each emergency contact belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
