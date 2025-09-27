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
        'document',
        'old_grade_id',
        'new_grade_id',
        'increment_amount'
    ];

    public function oldGrade()
    {
        return $this->belongsTo(\App\Models\SalaryGrade::class, 'old_grade_id');
    }

    public function newGrade()
    {
        return $this->belongsTo(\App\Models\SalaryGrade::class, 'new_grade_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

