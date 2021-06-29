<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserShift extends Model
{
    protected $fillable = [
        'role_id',
        'shift_id',
        'date',
        'user_id',
        'days'
    ];
}
