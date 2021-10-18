<?php

namespace App\Models;

use App\User;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
