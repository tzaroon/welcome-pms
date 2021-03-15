<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingHasRoom extends Model
{
    protected $table = 'booking_room';

    public function rateType() {

        return $this->belongsTo(RateType::class);
    }
}
