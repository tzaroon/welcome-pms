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
    public function user() {

        return $this->belongsTo(User::class);
    }
}
