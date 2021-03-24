<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class BookingHasRoom extends Model
{
    protected $table = 'booking_room';

    public function rateType() {

        return $this->belongsTo(RateType::class);
    }

    public function updateRoom($roomId) {
        
        $affected = DB::table('booking_room_guests')
              ->where('booking_id', $this->booking_id)
              ->where('room_id', $this->room_id)
              ->update(['room_id' => $roomId]);
            
        $this->room_id = $roomId;
        $this->save();
        return $affected;
    }

    public function updatePrices() {

        $booking = Booking::find($this->booking_id);

        $start = Carbon::parse($booking->reservation_from);
        $end =  Carbon::parse($booking->reservation_to);

        $days = $end->diffInDays($start);
        
        $date = $start;
        $priceIds = [];
        for($i=0; $i < $days; $i++) {

            $rateDate = $date->format('Y-m-d');
            $dailyPrice = new DailyPrice();
            $dailyPrice = $dailyPrice->where('date', $rateDate)
                ->where('rate_type_id', $this->rate_type_id)
                ->first();
             
            $priceIds[$this->room_id.$i]['product_price_id'] = $dailyPrice->product->price->id;
            $priceIds[$this->room_id.$i]['booking_room_id'] =  $this->id;

            $date = $date->addDay();
        }
        $booking->productPrice()->sync($priceIds);
        return;
    }
}
