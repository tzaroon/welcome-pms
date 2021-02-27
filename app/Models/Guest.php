<?php

namespace App\Models;

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
}
