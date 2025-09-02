<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttMachineData extends Model
{
    use HasFactory;

    public $table = 'att_machine_data';

    protected $fillable = [
        'punch_id',
        'date_time',
        'device_id',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];
}
