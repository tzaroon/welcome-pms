<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTypeImage extends Model
{
    protected $fillable = [
        'company_id',
        'room_type_id',
        'image',
    ];
}
