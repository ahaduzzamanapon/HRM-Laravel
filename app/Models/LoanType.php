<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'interest_rate',
        'max_installments',
        'loan_ceilings',
    ];

    protected $casts = [
        'loan_ceilings' => 'array',
    ];
}
