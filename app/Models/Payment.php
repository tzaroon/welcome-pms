<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_method',
        'initials',
        'payment_date',
        'amount'
    ];

    const TYPE_BANKCARD = 'bankcard';
    const TYPE_CASH = 'cash';
    const TYPE_GIFTCARD = 'giftcard';
    const TYPE_INVOICE = 'invoice';
    const TYPE_CREDITCARD = 'creditcard';

    public static $__types_array = [
        [
            'value' => self::TYPE_BANKCARD,
            'name' => 'Bank Card'
        ],
        [
            'value' => self::TYPE_CASH,
            'name' => 'Cash'
        ],
        [
            'value' => self::TYPE_INVOICE,
            'name' => 'Invoice'
        ],
        [
            'value' => self::TYPE_CREDITCARD,
            'name' => 'Credit Card'
        ]
    ];
}
