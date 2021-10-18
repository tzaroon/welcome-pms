<?php

namespace App\Models;
use App\User;

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
        self::SHIFT_MORNING => 'Morning',
        self::SHIFT_EVENING => 'Evening',
        self::DOCUMENT_NIGHT => 'Night'
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

    public function user() {
        return $this->belongsTo(User::class);
    }
}
