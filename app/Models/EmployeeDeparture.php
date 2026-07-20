<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeparture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'effective_date',
        'reason',
        'remarks',
        'document'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
