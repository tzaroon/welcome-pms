<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'user_id',
        'guest_type',
        'identification_number',
        'identification',
        'id_issue_date',
        'id_expiry_date'
    ];

    const GUEST_TYPE_ADULT = 'adult';
    const GUEST_TYPE_CHILD = 'child';
    const GUEST_TYPE_CORPORATE = 'corporate';

    static $__guest_types = [
        self::GUEST_TYPE_ADULT => 'Adult',
        self::GUEST_TYPE_CHILD => 'Child',
        self::GUEST_TYPE_CORPORATE => 'Corporate'
    ];

    static $__guest_types_array = [
        [
            'value' => self::GUEST_TYPE_ADULT,
            'name' => 'Adult'
        ],
        [
            'value' => self::GUEST_TYPE_CHILD,
            'name' => 'Child'
        ],
        [
            'value' => self::GUEST_TYPE_CORPORATE,
            'name' => 'Corporate'
        ]
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
