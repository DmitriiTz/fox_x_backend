<?php

namespace App;

use Illuminate\Notifications\Notifiable;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'referral_account_id'
    ];

    protected $appends = [
        'balance',
        'level',
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

        if(!$this->image) {
            return asset('img/fox.png');
        }

        return $this->image;
    }

    public function getBalanceAttribute() {
        $value = getBalance($this);
        return $value;
    }

    public function getLevelAttribute() {

        $value = getLevel($this);
        return $value;
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'account_id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
