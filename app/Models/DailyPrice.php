<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPrice extends Model
{
    protected $fillable = [
        'company_id',
        'date',
        'rate_type_id'
    ];
}
