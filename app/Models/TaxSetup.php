<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TaxSetup
 * @package App\Models
 * @version September 27, 2025, 9:03 am UTC
 *
 * @property string $titel
 * @property integer $min_salary
 * @property integer $max_salary
 * @property integer $tax_yearly
 * @property integer $tax_monthly
 * @property string $update_by
 */
class TaxSetup extends Model
{

    public $table = 'taxsetups';
    



    public $fillable = [
        'titel',
        'min_salary',
        'max_salary',
        'tax_yearly',
        'tax_monthly',
        'update_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'titel' => 'string',
        'min_salary' => 'integer',
        'max_salary' => 'integer',
        'tax_yearly' => 'integer',
        'tax_monthly' => 'integer',
        'update_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
