<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TaxSetup
 * @package App\Models
 * @version September 27, 2025, 9:03 am UTC
 *
 * @property string $title
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
        'title',
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
        'title' => 'string',
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
        'title' => 'required',
        'min_salary' => 'required|numeric',
        'max_salary' => 'required|numeric|gte:min_salary',
        'tax_yearly' => 'required|numeric',
        'tax_monthly' => 'required|numeric'
    ];

    
}
