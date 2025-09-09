<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Notice
 * @package App\Models
 * @version September 9, 2025, 5:24 am UTC
 *
 * @property string $title
 * @property string $status
 * @property string $documents
 * @property string $description
 */
class Notice extends Model
{

    public $table = 'notices';
    



    public $fillable = [
        'title',
        'status',
        'documents',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'status' => 'string',
        'documents' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
