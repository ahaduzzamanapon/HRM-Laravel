<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildAllowance extends Model
{
    use HasFactory;

    protected $casts = [
        'start_month' => 'date:Y-m',
        'end_month' => 'date:Y-m',
    ];

    protected $fillable = [
        'user_id',
        'child_name',
        'child_dob',
        'start_age',
        'start_month',
        'pay_year',
        'end_month',
        'pay_amt',
        'updated_by'
    ];
}
