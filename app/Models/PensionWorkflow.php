<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'trackable_id',
        'trackable_type',
        'stage_name',
        'status',
        'remarks',
        'action_by',
        'action_at',
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
