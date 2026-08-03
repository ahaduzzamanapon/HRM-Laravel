<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PfYearlyInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'year',
        'balance_before',
        'interest_amount',
        'balance_after'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
