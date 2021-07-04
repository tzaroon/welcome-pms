<?php

namespace App;

use App\Models\Booker;
use App\Models\Country;
use App\Models\Guest;
use App\Models\Language;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\HasJWT;
use App\Models\Role;

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
        'state_id',
        'birth_date',
        'username',
        'role_id'
    ];

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_NONE = 'none';

    static $__gender_array = [
        [
            'value' => self::GENDER_MALE,
            'name' => 'Male'
        ],
        [
            'value' => self::GENDER_FEMALE,
            'name' => 'Female'
        ],
        [
            'value' => self::GENDER_NONE,
            'name' => 'Not Spacified'
        ]
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array ok
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
        'birthday'  => 'date:Y-m-d'
    ];

    public function booker()
    {
        return $this->hasOne(Booker::class);
    }

    public function guest()
    {
        return $this->hasOne(Guest::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
