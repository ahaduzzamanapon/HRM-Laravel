<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id', 'user_id', 'assigned_date', 'expected_return_date', 
        'return_date', 'condition_on_assignment', 'condition_on_return', 
        'status', 'notes', 'assigned_by', 'returned_to'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function returnedTo()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }
}
