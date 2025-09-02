<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'day_of_week',
        'in_time',
        'out_time',
        'late_start_time',
        'lunch_start_time',
        'lunch_end_time',
        'is_weekend',
    ];

    protected $casts = [
        'is_weekend' => 'boolean',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
