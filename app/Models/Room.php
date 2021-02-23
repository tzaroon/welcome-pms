<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = ['company_id'];

    public function roomType() {

        return $this->belongsTo(RoomType::class);
    }
}
