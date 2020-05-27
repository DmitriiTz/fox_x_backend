<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function referrals() {
        return $this->hasMany(User::class, 'referral_account_id');
    }

    public function getImageAttribute($value) {

        if(!$value) {
            return asset('img/fox.png');
        }

        return $value;
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'account_id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
