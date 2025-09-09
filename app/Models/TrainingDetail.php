<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'training_name',
        'training_provider',
        'training_type',
        'document',
        'start_date',
        'end_date',
        'description'
    ];
}