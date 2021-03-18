<?php

namespace App;

use App\Models\Booker;
use App\Models\Guest;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\HasJWT;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasJWT;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 
        'title', 
        'first_name', 
        'last_name', 
        'gender', 
        'email', 
        'language_id', 
        'phone_number',
        'street',
        'postal_code',
        'city',
        'country_id',
        'birth_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'verified',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function booker() {
        return $this->hasOne(Booker::class);
    }
   
    public function guest() {
        return $this->hasOne(Guest::class);
    }
}
