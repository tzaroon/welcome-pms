<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $guarded = [];

    public function roomTypeDetail() {

        $language = Language::where(['is_default' => 1])->first();
        return $this->hasOne('App\Models\RoomTypeDetail')->where('language_id', $language->id);
    }
    
    public function roomTypeDetails() {

        return $this->hasMany('App\Models\RoomTypeDetail');
    }
    
    public function hotel() {

        return $this->belongsTo('App\Models\Hotel');
    }
    
    public function category() {

        return $this->belongsTo('App\Models\RoomCategory');
    }
}
