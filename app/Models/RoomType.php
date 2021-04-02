<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function roomTypeDetail() {

        $language = Language::where(['is_default' => 1])->first();
        return $this->hasOne(RoomTypeDetail::class)->where('language_id', $language->id);
    }
    
    public function roomTypeDetails() {

        return $this->hasMany(RoomTypeDetail::class);
    }
    
    public function hotel() {

        return $this->belongsTo(Hotel::class);
    }
    
    public function category() {

        return $this->belongsTo(RoomCategory::class);
    }
    
    public function rateTypes() {

        return $this->hasMany(RateType::class)->with('detail');
    }
}
