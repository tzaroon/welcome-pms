<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $appends = [
        'numberOfDays'
    ];

    public function room() {
        
        return $this->belongsTo(Room::class);
    }
    
    public function getNumberOfDaysAttribute() {

        return Carbon::parse($this->reservation_from)->diffInDays($this->reservation_to);
    }
}
