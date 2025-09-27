<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class BankSetup
 * @package App\Models
 * @version September 27, 2025, 5:32 am UTC
 *
 * @property string $bank_name
 * @property string $branch_name
 * @property string $address
 * @property string $bank_code
 * @property string $description
 */
class BankSetup extends Model
{

    public $table = 'banksetups';
    



    public $fillable = [
        'bank_name',
        'branch_name',
        'address',
        'bank_code',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bank_name' => 'string',
        'branch_name' => 'string',
        'address' => 'string',
        'bank_code' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bank_name' => 'required',
        'branch_name' => 'required',
        'address' => 'required',
        'bank_code' => 'required',
        'description' => 'nullable'
    ];

    
}
