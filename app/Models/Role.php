<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'company_id'        
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function shifts() {
        return $this->hasMany(RoleShift::class);
    }
}
