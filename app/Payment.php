<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function payment_system() {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id');
    }

    public function referral() {
        return $this->belongsTo(User::class, 'referral_account_id');
    }

    public function account() {
        return $this->belongsTo(User::class, 'account_id');
    }

    public function game() {
        return $this->belongsTo(HistoryGame::class, 'history_game_id');
    }
}
