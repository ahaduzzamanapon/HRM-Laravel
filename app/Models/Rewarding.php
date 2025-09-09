<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Rewarding
 * @package App\Models
 * @version September 9, 2025, 10:07 am UTC
 *
 * @property string $title
 * @property string $document
 * @property string $date
 * @property string $reason
 * @property string $description
 */
class Rewarding extends Model
{

    public $table = 'rewardings';
    



    public $fillable = [
        'user_id',
        'title',
        'document',
        'date',
        'reason',
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
        'document' => 'string',
        'date' => 'string',
        'reason' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    
}
