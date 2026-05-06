<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewMovementExpense extends Model
{
    use HasFactory;

    protected $table = 'new_movement_travel_expenses';

    protected $fillable = [
        'movement_id',
        'travel_id',
        'transport_type',
        'amount',
        'approve_amount',
        'note',
        'created_by',
    ];

    public function movement()
    {
        return $this->belongsTo(NewMovement::class, 'movement_id');
    }

    public function travel()
    {
        return $this->belongsTo(NewMovementTravel::class, 'travel_id');
    }
}
