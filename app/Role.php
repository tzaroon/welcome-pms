<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'company_id'        
    ];

    const DOCUMENT_PASSPORT = 'passport';
    const DOCUMENT_ID = 'id';
    const DOCUMENT_OTHERS = 'others';

    static $__document_types = [
        self::DOCUMENT_PASSPORT => 'Passport',
        self::DOCUMENT_ID => 'ID',
        self::DOCUMENT_OTHERS => 'Others'
    ];
    
    static $__document_types_array = [
        [
            'value' => self::DOCUMENT_PASSPORT,
            'name' => 'Passport'
        ],
        [
            'value' => self::DOCUMENT_ID,
            'name' => 'ID'
        ],
        [
            'value' => self::DOCUMENT_OTHERS,
            'name' => 'Others'
        ]
    ];

    public function user() {

        return $this->belongsTo(User::class);
    }
}
