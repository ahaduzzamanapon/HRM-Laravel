<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class SiteSetting
 * @package App\Models
 * @version November 21, 2024, 11:31 am UTC
 *
 * @property string $name
 * @property string $logo
 * @property string $slogan
 */
class SiteSetting extends Model
{

    public $table = 'sitesettings';
    



    public $fillable = [
        'name',
        'logo',
        'slogan'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'logo' => 'string',
        'slogan' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'slogan' => 'required'
    ];

    
}
