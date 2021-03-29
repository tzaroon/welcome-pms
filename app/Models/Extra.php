<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extra extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'hotel_id',
        'name',
        'description'
    ];

    public function settings() {
        return $this->belongsToMany(ExtraSetting::class, 'extras_has_extra_settings')->withPivot('value');
    }

    public function product() {

        return $this->belongsTo(Product::class);
    }
}