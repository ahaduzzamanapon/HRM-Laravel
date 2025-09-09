<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'support_date',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }
}
