<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryGame extends Model
{
    public function participants() {
        return $this->hasMany(Participant::class, 'history_game_id');
    }

    public function winner() {
        return $this->belongsTo(User::class, 'winner_account_id');
    }

    public function type() {
        return $this->belongsTo(GameType::class, 'game_type_id');
    }

    public function getLooserAttribute() {
        return $this->hasOne(Participant::class, 'history_game_id')->with('account')->where('account_id', '!=', $this->winner_account_id)->first();
    }

    public function nameGame() {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
