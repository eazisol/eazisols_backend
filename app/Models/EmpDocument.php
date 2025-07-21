<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpDocument extends Model
{
    use HasFactory;

    protected $table = 'emp_documents';

    protected $fillable = [
        'user_id',
        'resume',
        'id_proof',
        'address_proof',
        'offer_letter',
        'joining_letter',
        'contract_letter',
        'education_documents',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
