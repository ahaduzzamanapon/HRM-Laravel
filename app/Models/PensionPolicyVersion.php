<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionPolicyVersion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'policy_id',
        'version_number',
        'effective_date',
        'change_summary',
        'created_by',
        'created_at',
    ];

    public function policy()
    {
        return $this->belongsTo(PensionPolicy::class, 'policy_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
