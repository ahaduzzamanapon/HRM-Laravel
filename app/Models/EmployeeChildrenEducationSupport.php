<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeChildrenEducationSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'child_name',
        'exam_name',
        'gpa',
        'financial_assistance',
        'support_date',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }
}
