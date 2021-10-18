<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleShift extends Model
{
    protected $fillable = [
        'role_id',
        'name',
        'from_time',
        'to_time'
    ];
}
