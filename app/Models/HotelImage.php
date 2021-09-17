<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{   
    protected $fillable = [
        'company_id',
        'hotel_id',
        'image',
    ];
}



