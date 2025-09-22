<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryIncrement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'increment_date',
        'old_salary',
        'new_salary',
        'increment_amount',
        'document'
    ];
}