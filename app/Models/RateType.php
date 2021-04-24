<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_type_id',
        'company_id',
        'price'
    ];
    
    protected $appandes = [
        'rate_type_price'
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
}
