<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $guarded = [];

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
}
