<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Designation
 * @package App\Models
 * @version January 29, 2025, 5:40 am UTC
 *
 * @property string $desi_name
 * @property string $desi_status
 */
class Designation extends Model
{

    public $table = 'designations';
    



    public $fillable = [
        'desi_name',
        'desi_status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'desi_name' => 'string',
        'desi_status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'desi_name' => 'required',
        'desi_status' => 'required'
    ];

    
}
