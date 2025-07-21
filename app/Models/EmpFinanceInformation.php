<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpFinanceInformation extends Model
{
    use HasFactory;

    protected $table = 'emp_finance_information';

    protected $fillable = [
        'user_id',
        'basic_salary',
        'bank_name',
        'account_number',
        'payment_type',
    ];

    // Relationship: this finance info belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
