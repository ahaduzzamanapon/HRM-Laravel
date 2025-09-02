<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class SiteSetting
 * @package App\Models
 * @version November 21, 2024, 11:31 am UTC
 *
 * @property string $site_name
 * @property string $site_email
 * @property string $site_phone
 * @property string $site_address
 * @property string $site_logo
 * @property string $site_favicon
 * @property string $site_description
 * @property string $site_keywords
 * @property string $site_author
 * @property string $site_footer
 */
class SiteSetting extends Model
{

    public $table = 'sitesettings';
    



    public $fillable = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'site_logo',
        'site_favicon',
        'site_description',
        'site_keywords',
        'site_author',
        'site_footer'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'site_name' => 'string',
        'site_email' => 'string',
        'site_phone' => 'string',
        'site_address' => 'string',
        'site_logo' => 'string',
        'site_favicon' => 'string',
        'site_description' => 'string',
        'site_keywords' => 'string',
        'site_author' => 'string',
        'site_footer' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'site_name' => 'required',
        'site_email' => 'nullable|email',
        'site_phone' => 'nullable',
        'site_address' => 'nullable',
        'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'site_description' => 'nullable',
        'site_keywords' => 'nullable',
        'site_author' => 'nullable',
        'site_footer' => 'nullable'
    ];

    
}
