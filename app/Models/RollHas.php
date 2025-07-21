<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollHas extends Model
{
    use HasFactory;

    protected $fillable = [
        'roll_id',
        'permission_id'
    ];
}
