<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrashGame extends Model
{
    protected $table = 'crashgames';
    protected $fillable = ['price','number','status','create_game','rand_number', 'profit', 'stop_game'];
    const STATUS_NOT_STARTED = 0;
    const STATUS_PLAYING = 1;
    const STATUS_FINISHED = 2;
	public function bets()
    {
        return $this->hasMany('App\CrashBet');
    }


}
