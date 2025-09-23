<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_contribution',
        'employer_contribution',
        'interest_rate',
    ];
}
