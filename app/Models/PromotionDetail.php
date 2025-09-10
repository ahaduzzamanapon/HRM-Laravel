<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'promotion_date',
        'new_designation',
        'old_designation',
        'pay_grade_change',
        'new_salary',
        'document'
    ];
}

