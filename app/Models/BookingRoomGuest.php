<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRoomGuest extends Model
{
    protected $fillable = [
        'room_id',
        'booking_id',
        'guest_id'
    ];
}
