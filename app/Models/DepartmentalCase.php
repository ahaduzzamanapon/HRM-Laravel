<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentalCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'allegation_type',
        'allegation_category',
        'disciplinary_issue_details',
        'committee_comments',
        'penalty_id',
        'final_action_taken',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function penalty()
    {
        return $this->belongsTo(\App\Models\Penalty::class, 'penalty_id');
    }
}
