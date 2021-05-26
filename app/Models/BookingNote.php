<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingNote extends Model
{
    protected $fillable = [
        'booking_id',
        'note',
        'start',
        'end'
    ];
}
