<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewMovement extends Model
{
    use HasFactory;

    protected $table = 'new_movement_movements';

    protected $fillable = [
        'employee_id',
        'start_location',
        'start_latitude',
        'start_longitude',
        'purpose',
        'type',
        'start_time',
        'end_time',
        'start_photo',
        'status',
        'ta_status',
        'ta_amount',
        'ta_app_amt',
        'admin_note',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function travels()
    {
        return $this->hasMany(NewMovementTravel::class, 'movement_id');
    }

    public function meetings()
    {
        return $this->hasMany(NewMovementMeeting::class, 'movement_id');
    }

    public function expenses()
    {
        return $this->hasMany(NewMovementExpense::class, 'movement_id');
    }
}
