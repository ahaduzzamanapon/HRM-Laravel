<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PfApprovalWorkflow extends Model
{
    use HasFactory;

    protected $table = 'pf_approval_workflows';

    protected $fillable = [
        'model_type',
        'model_id',
        'approver_id',
        'level',
        'status',
        'comments'
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
