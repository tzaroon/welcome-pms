<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'role_id',
        'shift',
        'date',
        'user_id'
    ];

    const SHIFT_MORNING = 'morning';
    const SHIFT_EVENING = 'evening';
    const DOCUMENT_NIGHT = 'night';

    static $__shift_types = [
        self::SHIFT_MORNING => 'morning',
        self::SHIFT_EVENING => 'evening',
        self::DOCUMENT_NIGHT => 'night'
    ];
    
    static $__shift_types_array = [
        [
            'value' => self::SHIFT_MORNING,
            'name' => 'morning'
        ],
        [
            'value' => self::SHIFT_EVENING,
            'name' => 'evening'
        ],
        [
            'value' => self::DOCUMENT_NIGHT,
            'name' => 'night'
        ]
    ];    
}
