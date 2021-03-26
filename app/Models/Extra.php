<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    public function settings() {
        return $this->belongsToMany(ExtraSetting::class);
    }
}
