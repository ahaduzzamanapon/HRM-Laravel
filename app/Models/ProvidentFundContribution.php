<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'contribution_date',
        'employee_contribution',
        'employer_contribution',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }
}
