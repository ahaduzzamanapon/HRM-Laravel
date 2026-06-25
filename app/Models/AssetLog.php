<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'event_type',
        'user_id',
        'department_id',
        'action_by',
        'notes',
        'old_status',
        'new_status',
        'reference_type',
        'reference_id'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public static function logEvent($assetId, $eventType, $oldStatus, $newStatus, $notes = null, $userId = null, $departmentId = null, $reference = null)
    {
        return self::create([
            'asset_id' => $assetId,
            'event_type' => $eventType,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'user_id' => $userId,
            'department_id' => $departmentId,
            'notes' => $notes,
            'action_by' => auth()->id() ?? 1,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
        ]);
    }
}
