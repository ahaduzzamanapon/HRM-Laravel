<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewMovementMeeting extends Model
{
    use HasFactory;

    protected $table = 'new_movement_meetings';

    protected $fillable = [
        'movement_id',
        'entity_type',
        'client_name',
        'contact_person',
        'contact_email',
        'contact_phone',
        'contact_job_title',
        'meeting_type',
        'location',
        'latitude',
        'longitude',
        'remarks',
        'start_time',
        'end_time',
        'feedback',
        'crm_lead_id',
    ];

    public function movement()
    {
        return $this->belongsTo(NewMovement::class, 'movement_id');
    }
}
