<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateType extends Model
{
    public function detail() {
        $language = Language::where(['is_default' => 1])->first();
        return $this->hasOne(RateTypeDetail::class)->where('language_id', $language->id);
    }
}
