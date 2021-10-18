<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPrice extends Model
{
    protected $fillable = [
        'company_id',
        'date',
        'rate_type_id'
    ];

    public function rateType() {

        return $this->belongsTo(RateType::class);
    }
    
    public function product() {

        return $this->belongsTo(Product::class);
    }
}
