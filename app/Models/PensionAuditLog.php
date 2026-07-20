<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionAuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
