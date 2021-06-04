<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    protected $fillable = [
        'room_id',
        'arrival',
        'departure',
        'specific_days',
        'actions',
        'days',
        'cancellation_policies',
        'release'         
    ];
}
