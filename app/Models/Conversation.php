<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;
    // protected $dates = ['deleted_at'];

    protected $fillable = [
        'contact_detail_id',
        'from_user_id',
        'to_user_id',
        'message',
        'type',
        'is_viewed'
    ];
}
