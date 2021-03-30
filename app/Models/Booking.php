<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

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
        'price',
        'accessories'
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
    const STATUS_CHICKOUT = 'check-out';

    static $__status = [
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_CHICKIN => 'Check In',
        self::STATUS_CHICKOUT => 'Check Out'
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
        return $this->belongsToMany(ProductPrice::class, 'bookings_has_product_prices')->withPivot(['booking_has_room_id', 'extras_count']);
    }

    public function bookingRooms() {
        return $this->hasMany(BookingHasRoom::class);
    }
    
    public function getAccessoriesAttribute() {

        $productPrices = [];
        if($this->productPrice) {
            foreach($this->productPrice as $productPrice) {
                
                if($productPrice->product->extra) {
                    $productPrices[] = $productPrice->product->extra->name;
                }
            }
        }
        return $productPrices;
    }

    public function getPriceAttribute() {

        $guestsCount = DB::table('booking_room_guests')
            ->join('guests', 'booking_room_guests.guest_id', '=', 'guests.id')
            ->select('booking_room_guests.room_id', 'guests.guest_type', DB::raw('count(*) as guest_count'))
            ->where('booking_room_guests.booking_id', $this->id)
            ->groupBy('guests.guest_type')
            ->groupBy('booking_room_guests.room_id')
            ->get();

        $guestreport = [];
        if($guestsCount) {
            foreach($guestsCount as $count) {
                $guestreport[$count->room_id][$count->guest_type] = $count->guest_count;
            }
        }

        $totalPrice = 0;
        $onlyPrice = 0;
        $taxes = [];
        $prices = [];
        if($this->productPrice) {
           
            foreach($this->productPrice as $productPrice) {

                $bookingRoom = BookingHasRoom::find($productPrice->pivot->booking_has_room_id);

                $totalPrice = $totalPrice+$productPrice->price;
                $onlyPrice = $onlyPrice+$productPrice->price;
                $prices['price'] = $totalPrice;

                if($productPrice->taxes) {
                    //$allTaxes = [];
                    foreach($productPrice->taxes as $tax) {
                        $guestCount = 0;
                        if($bookingRoom && array_key_exists($bookingRoom->room_id, $guestreport)) {
                            //$allTaxes[] = $tax;
                            switch($tax->tax_id) {
                                case 1:
                                    $guestCount = array_key_exists(Guest::GUEST_TYPE_ADULT, $guestreport[$bookingRoom->room_id]) ? $guestreport[$bookingRoom->room_id][Guest::GUEST_TYPE_ADULT] : 0;
                                    $guestCount += array_key_exists(Guest::GUEST_TYPE_CORPORATE, $guestreport[$bookingRoom->room_id]) ? $guestreport[$bookingRoom->room_id][Guest::GUEST_TYPE_CORPORATE] : 0;
                                break;
                                case 2:
                                    $guestCount += array_key_exists(Guest::GUEST_TYPE_CHILD, $guestreport[$bookingRoom->room_id]) ? $guestreport[$bookingRoom->room_id][Guest::GUEST_TYPE_CHILD] : 0;
                                break;
                            }
                            if($tax->percentage) {
                                $taxAmount = $productPrice->price*$tax->percentage/100;
                                $totalPrice += ($taxAmount*$guestCount);
                                $prices['tax'] = $taxAmount*$guestCount;
                            } else {
                                $totalPrice += ($tax->amount*$guestCount);
                                $prices['tax'] = $tax->amount*$guestCount;
                            }
                        }
                    }
                }
                $prices['total'] = $totalPrice;
            }
        }
        $acuualPrice = array_key_exists('price', $prices) ? $prices['price'] : 0;
        $acuualTax = array_key_exists('tax', $prices) ? $prices['tax'] : 0;

        $prices['price'] = round($acuualPrice*90/100, 2);
        $prices['tax'] =  round($acuualTax*90/100, 2);
        $prices['vat'] = round(($acuualPrice*10/100)+($acuualTax*10/100), 2);
        return $prices;
    }
}
