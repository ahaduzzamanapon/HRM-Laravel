<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewMovementTravel extends Model
{
    use HasFactory;

    protected $table = 'new_movement_travels';

    protected $fillable = [
        'movement_id',
        'from_location',
        'to_location',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'start_time',
        'end_time',
        'distance',
        'distance_km',
        'status',
        'is_office_return',
    ];

    public function movement()
    {
        return $this->belongsTo(NewMovement::class, 'movement_id');
    }

    public function expenses()
    {
        return $this->hasMany(NewMovementExpense::class, 'travel_id');
    }
}
