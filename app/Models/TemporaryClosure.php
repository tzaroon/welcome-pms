<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryClosure extends Model
{
    protected $fillable = [
        'room_id',
        'from_date',
        'to_date',
        'reason'
    ];
}
