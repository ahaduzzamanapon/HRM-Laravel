<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'type_id',
        'vendor_id',
        'requested_by',
        'title',
        'description',
        'priority',
        'status', // pending, assigned, in_progress, completed, cancelled
        'requested_date',
        'scheduled_date',
        'completion_date',
        'cost',
        'warranty_expiry_date',
        'remarks',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function type()
    {
        return $this->belongsTo(MaintenanceType::class, 'type_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
