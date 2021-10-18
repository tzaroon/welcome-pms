<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserAttendance extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'date',
        'attendance'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
