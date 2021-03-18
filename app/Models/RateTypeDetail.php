<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateTypeDetail extends Model
{
    protected $fillable = [
        'rate_type_id',
        'company_id',
        'language_id'
    ];
}
