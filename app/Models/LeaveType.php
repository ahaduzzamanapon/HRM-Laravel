<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_days_per_year',
        'gender_criteria',
    ];

    protected $casts = [
        'total_days_per_year' => 'integer',
    ];
}
