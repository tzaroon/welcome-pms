<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    protected $fillable = [
        'permission_id',
        'role_id'

    ];
}
