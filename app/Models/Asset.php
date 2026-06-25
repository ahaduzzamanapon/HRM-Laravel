<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'asset_code', 'name', 'brand', 'model', 
        'serial_number', 'purchase_date', 'purchase_cost', 
        'warranty_expiry_date', 'location', 'department_id', 
        'status', 'notes'
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
