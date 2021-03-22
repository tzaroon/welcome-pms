<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'company_id',
        'booker_id',
        'reservation_from',
        'reservation_to',
        'time_start',
        'status',
        'source',
        'comment'
    ];
    
    protected $appends = [
        'numberOfDays',
        'roomCount',
        'price'
    ];

    const SOURCE_BUSINESS = 'business';
    const SOURCE_GOOGLE = 'google';
    const SOURCE_OTHER = 'other';
    const SOURCE_DIRECT = 'direct';

    static $__sources = [
        self::SOURCE_BUSINESS => 'Business',
        self::SOURCE_GOOGLE => 'Google',
        self::SOURCE_OTHER => 'Other',
        self::SOURCE_DIRECT => 'Direct'
    ];

    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CHICKIN = 'check-in';

    static $__status = [
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_CHICKIN => 'Check In'
    ];

    const PAYMENT_STATUS_NOT_PAID = 'not-paid';
    const PAYMENT_STATUS_PARTIALLY_PAID = 'partially-paid';
    const PAYMENT_STATUS_PAID = 'paid';

    static $__payment_status = [
        self::PAYMENT_STATUS_NOT_PAID => 'Not Paid',
        self::PAYMENT_STATUS_PARTIALLY_PAID => 'Partially paid',
        self::PAYMENT_STATUS_PAID => 'Paid'
    ];

    public function rooms() {
        
        return $this->belongsToMany(Room::class);
    }
    
    public function getRoomCountAttribute() {
        
        return $this->belongsToMany(Room::class)->count();
    }
    
    public function getNumberOfDaysAttribute() {

        return Carbon::parse($this->reservation_from)->diffInDays($this->reservation_to);
    }

    public function booker() {

        return $this->belongsTo(Booker::class);
    }

    public function guests() {

        return $this->belongsToMany(Guest::class, 'booking_room_guests', 'booking_id')->withPivot('room_id');
    }

    public function productPrice() {
        return $this->belongsToMany(ProductPrice::class, 'bookings_has_product_prices')->withPivot(['booking_room_id']);
    }

    public function bookingRooms() {
        return $this->hasMany(BookingHasRoom::class);
    }

    public function getPriceAttribute() {

        $totalPrice = 0;
        if($this->productPrice) {
            foreach($this->productPrice as $productPrice) {
                $totalPrice += $productPrice->price;
            }
        }
        return $totalPrice;
    }
}
