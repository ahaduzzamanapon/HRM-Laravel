<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelfareFundAuditLog extends Model
{
    use HasFactory;

    protected $table = 'welfare_fund_audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'support_type',
        'support_id',
        'old_status',
        'new_status',
        'remarks',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
