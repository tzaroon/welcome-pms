<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = ['company_id'];

    protected $appends = [
        'ocupancy'
    ];

    public function roomType() {

        return $this->belongsTo(RoomType::class);
    }
    
    public function getOcupancyAttribute() {

        return '1-6';
    }
}
