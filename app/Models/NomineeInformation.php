<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomineeInformation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nominee_name',
        'relation',
        'voter_id',
        'percentage',
        'photo',
    ];
}