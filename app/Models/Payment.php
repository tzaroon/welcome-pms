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
}
