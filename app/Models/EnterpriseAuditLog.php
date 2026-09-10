<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterpriseAuditLog extends Model
{
    protected $table = 'enterprise_audit_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'branch_id',
        'module',
        'permission_key',
        'action',
        'record_id',
        'record_type',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
