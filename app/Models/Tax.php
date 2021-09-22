<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    const CITY_TAX = 1;
    const CHILDREN_CITY_TAX = 2;
    const VAT = 3;


    const FOUR_POINT_FIVE_PERCENT = '4.5';
    const EIGHT_PERCENT = '8';
    const TEN_PERCENT = '10';
    const NINETEEN_PERCENT = '19';
    const TWENTY_ONE_PERCENT = '21';

    static $__vat_percents = [
        self::FOUR_POINT_FIVE_PERCENT => '4.5%',
        self::EIGHT_PERCENT => '8%',
        self::TEN_PERCENT => '10%',
        self::NINETEEN_PERCENT => '19%',
        self::TWENTY_ONE_PERCENT => '21%',
    ];
    
    static $__vat_percents_array = [
        [
            'value' => self::FOUR_POINT_FIVE_PERCENT,
            'name' => '4.5%'
        ],
        [
            'value' => self::EIGHT_PERCENT,
            'name' => '8%'
        ],
        [
            'value' => self::TEN_PERCENT,
            'name' => '10%'
        ],
        [
            'value' => self::NINETEEN_PERCENT,
            'name' => '19%'
        ],
        [
            'value' => self::TWENTY_ONE_PERCENT,
            'name' => '21%'
        ]
    ];
}
