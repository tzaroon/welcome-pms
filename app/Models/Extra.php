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

    const PRICING_BY_DAY = 'by_day';
    const PRICING_BY_PERSON_PER_DAY = 'by_person_per_day';
    const PRICING_BY_PERSON_PER_STAY = 'by_person_per_stay';
    const PRICING_BY_FULL_STAY = 'full_stay';

    static $__pricing = [
        self::PRICING_BY_DAY => 'By Day',
        self::PRICING_BY_PERSON_PER_DAY => 'By person per day',
        self::PRICING_BY_PERSON_PER_STAY => 'By person per stay',
        self::PRICING_BY_FULL_STAY => 'Full Stay'
    ];

    static $__pricing_array = [
        [
            'value' => self::PRICING_BY_DAY,
            'name' => 'By Day'
        ],
        [
            'value' => self::PRICING_BY_PERSON_PER_DAY,
            'name' => 'By person per day'
        ],
        [
            'value' => self::PRICING_BY_PERSON_PER_STAY,
            'name' => 'By person per stay'
        ],
        [
            'value' => self::PRICING_BY_FULL_STAY,
            'name' => 'Full Stay'
        ]
    ];

    public function settings() {
        return $this->belongsToMany(ExtraSetting::class, 'extras_has_extra_settings')->withPivot('value');
    }

    public function product() {

        return $this->belongsTo(Product::class);
    }
}