<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'amount',
        'repayment_date',
        'remarks',
    ];

    public function loan()
    {
        return $this->belongsTo(\App\Models\Loan::class, 'loan_id');
    }
}
