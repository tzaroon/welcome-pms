<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateType extends Model
{
    use SoftDeletes;

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
}
