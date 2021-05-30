<?php

namespace App\Models;

use App\Dto\BookingQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_type_id',
        'company_id',
        'price',
        'number_of_people',
        'advance'
    ];
    
    protected $appandes = [
        'rate_type_price',
        'rate_row'
    ];

    public $rateDate;

    public function detail() {
        $language = Language::where(['is_default' => 1])->first();
        return $this->hasOne(RateTypeDetail::class)->where('language_id', $language->id);
    }
    
    public function details() {

        return $this->hasMany(RateTypeDetail::class);
    }

    public function roomType() {

        return $this->belongsTo(RoomType::class);
    }

    public function rateType() {

        return $this->belongsTo(RateType::class);
    }

    public function rateTypes() {

        return $this->hasMany(RateType::class);
    }

    public function getRateTypePriceAttribute() {

        if($this->rate_type_id) {
            $masterRateType = $this->find($this->rate_type_id);
            if(!is_null($this->percent_to_add)) {
                return $masterRateType->price + ($masterRateType->price*$this->percent_to_add)/100;
            }
            
            if(!is_null($this->amount_to_add)) {
                return $masterRateType->price + $this->amount_to_add;
            }
        }
        return $this->price;
    }
    public function bookings() {

		return $this->belongsToMany(Booking::class, 'booking_room');
	}

    public function dailyPrice() {
        return $this->hasOne(DailyPrice::class)->where('date', $this->rateDate);
    }

    public function calculateRate(BookingQuery $bookingQuery) {

        $dailyPrices = DailyPrice::where('rate_type_id', $this->id)->where('date', '>=',$bookingQuery->reservationFrom)->where('date', '<',$bookingQuery->reservationTo)->get();
        $price = 0;
        $calculatedPrices = [];
        if($dailyPrices->count()) {
            $taxesArr = [
                Tax::CITY_TAX => 0,
                Tax::CHILDREN_CITY_TAX => 0,
                Tax::VAT => 0
            ];

            foreach($dailyPrices as $dailyPrice) {
                $product = $dailyPrice->product;
                $price += $dailyPrice->product->price->price;
                $taxes = $dailyPrice->product->price->taxes;
                
                if($taxes->count()) {
                    foreach($taxes as $tax) {
                        if($tax->amount) {
                            $taxesArr[$tax->tax_id] += $tax->amount;
                        } elseif($tax->percentage) {
                            $amount = $tax->percentage/100*$dailyPrice->product->price->price;
                            $taxesArr[$tax->tax_id] += $amount;
                        }
                    }
                }
            }
            if($price > 0) {
                $calculatedPrices = [
                    'id' => $this->id,
                    'room_type' => $this->roomType->roomTypeDetail->name,
                    'rate_type' => $this->detail->name,
                    'price_without_vat' => $price-(10/100*$price),
                    'price_with_vat' => $price,
                    'city_tax' => $taxesArr[Tax::CITY_TAX]*$bookingQuery->numberOfAdults,
                    'children_city_tax' => $taxesArr[Tax::CHILDREN_CITY_TAX]*$bookingQuery->numberOfChildren,
                    'vat' => 10/100*$price,
                    'cleanning' => $this->cleanning_price,
                    'total_price' => $price+($taxesArr[Tax::CITY_TAX]*$bookingQuery->numberOfAdults)+($taxesArr[Tax::CHILDREN_CITY_TAX]*$bookingQuery->numberOfChildren)+$this->cleanning_price
                ];
            }
        }
        return $calculatedPrices;
    }
}
