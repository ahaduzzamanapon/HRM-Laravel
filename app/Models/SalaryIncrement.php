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
        'document',
        'old_grade_id',
        'new_grade_id'
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