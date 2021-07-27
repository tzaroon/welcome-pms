<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    protected $fillable = [
        'amount',
        'booking_id',
        'payment_url',
    ];

    protected $casts = [
        'amount' => 'float'
    ];
}