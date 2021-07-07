<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'payment_method',
        'initials',
        'payment_date',
        'amount',
        'operation_code',
        'payment_on_account',
        'send_receipt',
        'notes'
    ];


    protected $appends = [
        'formated_amount',
        'decimal_amount'
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

    protected $casts = [
        'amount' => 'float'
    ];

    public function booking() {

        return $this->belongsTo(Booking::class);
    }

    public function getFormatedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', '.');
    }
    
    public function getDecimalAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function assignment() {
        return $this->hasOne(PaymentAssignment::class)->with(['assignedBy', 'assignedTo'])->latest();
    }
}
