<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transfer_date',
        'old_branch',
        'new_branch',
        'reason',
        'status',
        'document',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    public function oldBranchName()
    {
        return $this->belongsTo(Branch::class, 'old_branch', 'id');
    }

    public function newBranchName()
    {
        return $this->belongsTo(Branch::class, 'new_branch', 'id');
    }
}