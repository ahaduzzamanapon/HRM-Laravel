<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'innovation_type',
        'submission_date',
        'verifier_id',
        'verification_status',
        'verification_date',
        'remarks',
        'document',
    ];
    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function verifier()
    {
        return $this->belongsTo(\App\Models\User::class, 'verifier_id');
    }
}
