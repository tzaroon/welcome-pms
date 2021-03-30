<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Booker extends Model
{
    protected $fillable = [
        'company',
        'cif',
        'additional_information',
        'sent_auto_emails',
        'identification',
        'identification_number',
        'identification_date_of_expiry',
        'passport_country_id',
        'discount_amount',
        'discount_percentage'
    ];

    const DOCUMENT_PASSPORT = 'passport';
    const DOCUMENT_ID = 'id';
    const DOCUMENT_OTHERS = 'others';

    static $__document_types = [
        self::DOCUMENT_PASSPORT => 'Passport',
        self::DOCUMENT_ID => 'ID',
        self::DOCUMENT_OTHERS => 'Others'
    ];

    public function user() {

        return $this->belongsTo(User::class);
    }
}
