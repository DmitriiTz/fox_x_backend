<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawMoneyAccountApplication extends Model
{
    protected $with = ['account', 'payment_system'];
    public function account() {
        return $this->belongsTo(User::class, 'account_id');
    }

    public function payment_system() {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id');
    }
}
