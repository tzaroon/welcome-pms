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
        'wubook_response',
        'source',
        'comment',
        'tourist_tax',
        'discount'
    ];
    
    protected $appends = [
        'numberOfDays',
        'roomCount',
        'price',
        'accessories',
        'accessoriesObjects',
        'totalPaid'
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
    const STATUS_WAITING_APPROVAL = 'waiting for approval';
    const STATUS_REFUSED = 'refused';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CANCELLED_WITH_PANELATY = 'cancelled with penalty';

    static $__status = [
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_CHICKIN => 'Check In',
        self::STATUS_CHICKOUT => 'Check Out',
        self::STATUS_WAITING_APPROVAL => 'waiting for approval',
        self::STATUS_REFUSED => 'refused',
        self::STATUS_ACCEPTED => 'accepted',
        self::STATUS_CANCELLED => 'cancelled',
        self::STATUS_CANCELLED_WITH_PANELATY => 'cancelled with penalty'
    ];
    
    static $__status_array = [
        [
            'name' => 'Confirmed',
            'value' => self::STATUS_CONFIRMED
        ],
        [
            'name' => 'Check In',
            'value' => self::STATUS_CHICKIN
        ],
        [
            'name' => 'Check Out',
            'value' => self::STATUS_CHICKOUT
        ]
    ];

    const PAYMENT_STATUS_NOT_PAID = 'not-paid';
    const PAYMENT_STATUS_PARTIALLY_PAID = 'partially-paid';
    const PAYMENT_STATUS_PAID = 'paid';

    static $__payment_status = [
        self::PAYMENT_STATUS_NOT_PAID => 'Not Paid',
        self::PAYMENT_STATUS_PARTIALLY_PAID => 'Partially paid',
        self::PAYMENT_STATUS_PAID => 'Paid'
    ];
    static $__payment_status_array = [
        [
            'value' => self::PAYMENT_STATUS_NOT_PAID,
            'name' => 'Not Paid'
        ],
        [
            'value' => self::PAYMENT_STATUS_PARTIALLY_PAID,
            'name' => 'Partially paid'
        ],
        [
            'value' => self::PAYMENT_STATUS_PAID,
            'name' => 'Paid'
        ]
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
        return $this->belongsToMany(ProductPrice::class, 'bookings_has_product_prices')->withPivot(['booking_has_room_id', 'extras_count', 'extras_pricing', 'extras_date']);
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
    
    public function getAccessoriesObjectsAttribute() {

        $productPrices = [];
        if($this->productPrice) {
            foreach($this->productPrice as $productPrice) {
                
                if($productPrice->product->extra) {
                    $productPrices[] = $productPrice;
                }
            }
        }
        return $productPrices;
    }

    public function getPriceAttribute() {

        $guestsCount = DB::table('booking_room_guests')
            ->join('guests', 'booking_room_guests.guest_id', '=', 'guests.id')
            ->join('users', 'guests.user_id', '=', 'users.id')
            ->select('booking_room_guests.room_id', 'guests.guest_type','users.first_name', DB::raw('count(*) as guest_count'))
            ->where('booking_room_guests.booking_id', $this->id)
            ->groupBy('guests.guest_type')
            ->groupBy('users.first_name')
            ->groupBy('booking_room_guests.room_id')
            ->get();

        $guestreport = [];
        $guestsTotal = 0;
        
        if($guestsCount) {
            foreach($guestsCount as $count) {
                if($count->first_name) {
                    $guestType = $count->guest_type ? : Guest::GUEST_TYPE_ADULT;
                    if(array_key_exists($count->room_id, $guestreport) && array_key_exists($guestType, $guestreport[$count->room_id])) {
                        $guestreport[$count->room_id][$guestType] += $count->guest_count;
                    } else {
                        $guestreport[$count->room_id][$guestType] = $count->guest_count;
                    }

                    $guestsTotal += $count->guest_count;
                }
            }
        }

        $totalPrice = 0;
        $onlyPrice = 0;
        $accessoryVat = 0;
        $taxes = [];
        $prices = [];
        $prices['tax'] = 0;
        $prices['price'] = 0;
        $dailyPrices = [];

        if($this->productPrice) {
           
            $bookingDate = Carbon::parse($this->reservation_from);

            foreach($this->productPrice as $productPrice) {
                
                $totalDayPrice = 0;
                $priceDate = '';
                
                if($productPrice->pivot->booking_has_room_id) {
                    $bookingRoom = BookingHasRoom::find($productPrice->pivot->booking_has_room_id);

                    $totalPrice = $totalPrice+$productPrice->price;
                    $onlyPrice = $onlyPrice+$productPrice->price;

                    $dailyPrice = $productPrice->product->dailyPrice;
                    $prices['price'] = $onlyPrice;
                    $priceDate = $dailyPrice->date;
                    
                    $totalDayPrice += $productPrice->price;

                   // $bookingDate = $bookingDate->addDay();

                    if($productPrice->taxes) {
                        //$allTaxes = [];
                        foreach($productPrice->taxes as $tax) {
                            $guestCount = 0;
                            if($bookingRoom && array_key_exists($bookingRoom->room_id, $guestreport)) {
                                //$allTaxes[] = $tax;
                                switch($tax->tax_id) {
                                    case Tax::CITY_TAX:
                                        $guestCount = array_key_exists(Guest::GUEST_TYPE_ADULT, $guestreport[$bookingRoom->room_id]) ? $guestreport[$bookingRoom->room_id][Guest::GUEST_TYPE_ADULT] : 0;
                                        $guestCount += array_key_exists(Guest::GUEST_TYPE_CORPORATE, $guestreport[$bookingRoom->room_id]) ? $guestreport[$bookingRoom->room_id][Guest::GUEST_TYPE_CORPORATE] : 0;
                                    break;
                                    case Tax::CHILDREN_CITY_TAX:
                                        $guestCount += array_key_exists(Guest::GUEST_TYPE_CHILD, $guestreport[$bookingRoom->room_id]) ? $guestreport[$bookingRoom->room_id][Guest::GUEST_TYPE_CHILD] : 0;
                                    break;
                                }
                                if($tax->percentage) {
                                    $taxAmount = $productPrice->price*$tax->percentage/100;
                                    $totalPrice += ($taxAmount*$guestCount);
                                    $prices['tax'] += $taxAmount*$guestCount;

                                    $totalDayPrice +=  $taxAmount*$guestCount;
                                } else {
                                    $totalPrice += ($tax->amount*$guestCount);
                                    $prices['tax'] += $tax->amount*$guestCount;
                                    $totalDayPrice +=  $tax->amount*$guestCount;
                                }
                            }
                        }
                    }
                    if(array_key_exists($priceDate, $dailyPrices)) {
                        $dailyPrices[$priceDate] += $totalDayPrice;
                    } else {
                        $dailyPrices[$priceDate] = $totalDayPrice;
                    }
                    
                }    
            }
            
            foreach($this->productPrice as $productPrice) {
                
                if(!$productPrice->pivot->booking_has_room_id) {

                    $criteria = $productPrice->pivot->extras_pricing;
                    $date = $productPrice->pivot->extras_date;
                    if($date) {

                        $priceDate = $date;
                        $dailyPricesx = $dailyPrices;
                        $vat = $productPrice->price/100*$productPrice->vat->percentage;
                        if($dailyPricesx) {
                            foreach($dailyPricesx as $date=>$price){
                                if($priceDate == $date) {
                                    $dailyPrices[$date] = round($price+$vat+$productPrice->price, 2);
                                }
                            }
                        }
                    } else {
                        switch($criteria) {
                            case Extra::PRICING_BY_DAY:

                                $prices['price'] += $this->numberOfDays*$productPrice->price;
                                $totalPrice += $this->numberOfDays*$productPrice->price;
                                $vat = $productPrice->price/100*$productPrice->vat->percentage;
                                $totalPrice += $this->numberOfDays*$vat;
                                $accessoryVat += $vat*$this->numberOfDays;
                                $dailyPricesx = $dailyPrices;
                                if($dailyPricesx) {
                                    foreach($dailyPricesx as $date=>$price){
                                        $dailyPrices[$date] = round($price+$vat+$productPrice->price, 2);
                                    }
                                }
                                break;

                            case Extra::PRICING_BY_FULL_STAY:

                                $prices['price'] += $productPrice->price;
                                $totalPrice += $productPrice->price;
                                $vat = $productPrice->price/100*$productPrice->vat->percentage;
                                $totalPrice += $vat;
                                $accessoryVat += $vat;
                                $totalDayPrice +=  $vat/$this->numberOfDays;
                                $dailyPricesx = $dailyPrices;
                                if($dailyPricesx) {
                                    foreach($dailyPricesx as $date=>$price){
                                        $dailyPrices[$date] = round(($price+$vat/$this->numberOfDays+$productPrice->price)/$this->numberOfDays, 2);
                                    }
                                }
                                break;
                            case Extra::PRICING_BY_PERSON_PER_DAY:
                                $prices['price'] += $this->numberOfDays*$productPrice->price*$guestsTotal;
                                $totalPrice += $this->numberOfDays*$productPrice->price*$guestsTotal;
                                $vat = ($productPrice->price/100*$productPrice->vat->percentage)*$guestsTotal;
                                $totalPrice += $this->numberOfDays*$vat;
                                $accessoryVat += $vat*$this->numberOfDays;

                                $dailyPricesx = $dailyPrices;
                                if($dailyPricesx) {
                                    foreach($dailyPricesx as $date=>$price){
                                        $dailyPrices[$date] = round(($price+$vat+($productPrice->price*$guestsTotal)), 2);
                                    }
                                }
                                break;
                            case Extra::PRICING_BY_PERSON_PER_STAY:
                                $prices['price'] += $productPrice->price*$guestsTotal;
                                $totalPrice += $productPrice->price*$guestsTotal;
                                $vat = ($productPrice->price/100*$productPrice->vat->percentage)*$guestsTotal;
                                $totalPrice += $vat;
                                $accessoryVat += $vat;
                                $totalDayPrice += $vat/$this->numberOfDays;
                                
                                $dailyPricesx = $dailyPrices;
                                if($dailyPricesx) {
                                    foreach($dailyPricesx as $date=>$price){
                                        $dailyPrices[$date] = round(($price+($vat/$this->numberOfDays)+(($productPrice->price/$this->numberOfDays)*$guestsTotal)), 2);
                                    }
                                }

                                break;
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

        $prices['total'] = array_key_exists('total', $prices) ? round(($prices['total'] + $this->tourist_tax - $this->discount) - $this->totalPaid, 2) : 0;

        if(isset($accessoryVat)) {
            $prices['vat'] += round($accessoryVat, 2);
            $prices['vat'] = round($prices['vat'], 2);
        }

        $keyedDailyPrices = [];
        $gTotal = 0;
        if( $dailyPrices) {
            foreach( $dailyPrices as $date=>$price) {
                $keyedDailyPrices[] = [
                    'date' => $date,
                    'value' => number_format($price, 2, ',', '.')
                ];
                $gTotal += $price;
            } 
        }
        
        $prices['price_breakdown'] = [
            'daily_prices' => $keyedDailyPrices,
            'total_price' => round(($gTotal + $this->tourist_tax - $this->discount) - $this->totalPaid, 2)
        ];

        return $prices;
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute() {
        $totalPayment = 0;
        if($this->payments) {
            foreach($this->payments as $payment) {
                $totalPayment += $payment->amount;
            }
        }
        return $totalPayment;
    }

    public function getAccomudationPrice() {

        $totalPrice = 0;
        if($this->onlyAccomudationPrices) {
            foreach($this->onlyAccomudationPrices as $price) {
                $totalPrice += 90/100*$price->price;
            }
        }

        return round($totalPrice, 2);
    }
    
    public function getAccessoriesPrice() {

        $totalPrice = 0;
        if($this->onlyAccessoriesPrices) {
            foreach($this->onlyAccessoriesPrices as $price) {
                $criteria = $price->pivot->extras_pricing;
                $date = $price->pivot->extras_date;
                if($date) {

                    $totalPrice += $price->price;
                } else {
                    switch($criteria) {
                        case Extra::PRICING_BY_DAY:

                            $totalPrice += $this->numberOfDays*$price->price;
                            break;

                        case Extra::PRICING_BY_FULL_STAY:

                            $totalPrice += $price->price;
                            break;
                        case Extra::PRICING_BY_PERSON_PER_DAY:
                            $totalPrice += $price->price*($this->getAdultGuestCount()+$this->getChildrenGuestsCount())*$this->numberOfDays;
                            break;
                        case Extra::PRICING_BY_PERSON_PER_STAY:
                            $totalPrice += $price->price*($this->getAdultGuestCount()+$this->getChildrenGuestsCount());
                            break;
                    }
                }
            }
        }

        return round($totalPrice, 2);
    }
    
    public function getCityTax() {

        $totalCityTax = 0;
        if($this->onlyAccomudationPrices) {
            foreach($this->onlyAccomudationPrices as $price) {
                if($price->cityTax)
                {
                    $taxAmont = $price->cityTax->amount;
                    if($price->cityTax->percentage) {
                        $taxAmont = $price->cityTax->percentage/100*$price->price;
                    }
                    $totalCityTax += $taxAmont;
                }
            }
        }

        $totalCityTax = $totalCityTax*$this->getAdultGuestCount()*$this->numberOfDays;
        return round($totalCityTax, 2);
    }
    
    public function getChildrenCityTax() {

        $totalChildrenCityTax = 0;
        if($this->onlyAccomudationPrices) {
            foreach($this->onlyAccomudationPrices as $price) {
                if($price->childrenCityTax)
                {
                    $taxAmont = $price->childrenCityTax->amount;
                    if($price->childrenCityTax->percentage) {
                        $taxAmont = $price->childrenCityTax->percentage/100*$price->price;
                    }
                    $totalChildrenCityTax += $taxAmont;
                }
            }
        }

        $totalChildrenCityTax = $totalChildrenCityTax*$this->getChildrenGuestsCount()*$this->numberOfDays;
        return round($totalChildrenCityTax, 2);
    }
  
    public function getVat() {

        $totalVat = 0;
        if($this->onlyAccomudationPrices) {
            foreach($this->onlyAccomudationPrices as $price) {
                $totalVat += 10/100*$price->price;
            }
        }

        if($this->onlyAccessoriesPrices) {
            foreach($this->onlyAccessoriesPrices as $price) {
                $criteria = $price->pivot->extras_pricing;
                $date = $price->pivot->extras_date;
                if($date) {

                    $totalVat += $price->vat->percentage/100*$price->price;
                } else {
                    switch($criteria) {
                        case Extra::PRICING_BY_DAY:

                            $totalVat += $this->numberOfDays*($price->vat->percentage/100*$price->price);
                            break;

                        case Extra::PRICING_BY_FULL_STAY:

                            $totalVat += $price->vat->percentage/100*$price->price;
                            break;
                        case Extra::PRICING_BY_PERSON_PER_DAY:
                            $totalVat += $this->numberOfDays*($price->vat->percentage/100*$price->price)*($this->getAdultGuestCount()+$this->getChildrenGuestsCount());
                            break;
                        case Extra::PRICING_BY_PERSON_PER_STAY:
                            
                            $totalVat += ($price->vat->percentage/100*$price->price)*($this->getAdultGuestCount()+$this->getChildrenGuestsCount());
                            break;
                    }
                }
            }
        }

        return round($totalVat, 2);
    }

    public function getAdultGuestCount() {
        return $this->adultGuests->count();
    }
    
    public function getChildrenGuestsCount() {
        return $this->childrenGuests->count();
    }

    public function adultGuests() {

        return $this->belongsToMany(Guest::class, 'booking_room_guests', 'booking_id')->where('guest_type', Guest::GUEST_TYPE_ADULT)->withPivot('room_id');
    }
    
    public function childrenGuests() {

        return $this->belongsToMany(Guest::class, 'booking_room_guests', 'booking_id')->where('guest_type', Guest::GUEST_TYPE_CHILD)->withPivot('room_id');
    }

    public function onlyAccomudationPrices() {

        return $this->belongsToMany(ProductPrice::class, 'bookings_has_product_prices')->whereNotNull('booking_has_room_id')->withPivot(['booking_has_room_id', 'extras_count', 'extras_pricing', 'extras_date']);
    }
    
    public function onlyAccessoriesPrices() {

        return $this->belongsToMany(ProductPrice::class, 'bookings_has_product_prices')->whereNull('booking_has_room_id')->withPivot(['booking_has_room_id', 'extras_count', 'extras_pricing', 'extras_date']);
    }

    public function notes() {
        return $this->hasMany(BookingNote::class);
    }
}
