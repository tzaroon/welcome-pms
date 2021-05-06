<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class BookingHasRoom extends Model
{
    protected $table = 'booking_room';

    protected $fillable = [
        'booking_id',
        'room_id',
        'rate_type_id',
        'first_guest_name'
    ];
    protected $appends = [
        'price'
    ];
    public function rateType() {

        return $this->belongsTo(RateType::class);
    }
   
    public function room() {

        return $this->belongsTo(Room::class);
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
            $priceIds[$this->room_id.$i]['booking_has_room_id'] =  $this->id;

            $date = $date->addDay();
        }
        $booking->productPrice()->sync($priceIds);
        return;
    }

    public function productPrice() {
        return $this->belongsToMany(ProductPrice::class, 'bookings_has_product_prices')->withPivot(['booking_id', 'product_price_id']);
    }
    
    public function guests() {
        return DB::table('booking_room_guests')
                ->join('guests', 'guests.id', '=', 'booking_room_guests.guest_id')
                ->join('users', 'guests.user_id', '=', 'users.id')
                ->where('booking_id', $this->booking_id)
                ->where('room_id', $this->room_id)
                ->select('guests.id as guest_id', 'guests.*', 'users.*')
                ->get();
    }

    public function getPriceAttribute() {

        $this->room->roomType->rateType;
        $totalPrice = 0;
        if($this->productPrice) {
            foreach($this->productPrice as $productPrice) {
                $totalPrice += $productPrice->price;
            }
        }

        return $totalPrice;
    }

    public function productPriceByBookingId($bookingId) {

        $price = $this->belongsToMany(ProductPrice::class, 'bookings_has_product_prices')->withPivot(['booking_id', 'product_price_id'])->wherePivot('booking_id', '=', $bookingId)->first();
        if($price) {
            return $price->price;
        }
        return 0;
    }
}
