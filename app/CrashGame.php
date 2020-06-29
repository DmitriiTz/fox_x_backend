<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrashGame extends Model
{
    protected $table = 'crashgames';
    protected $fillable = ['price','number','status','create_game','rand_number', 'profit', 'stop_game'];
    protected $with = ['bets'];
    protected $appends = ['ubets'];
    const STATUS_NOT_STARTED = 0;
    const STATUS_PLAYING = 1;
    const STATUS_FINISHED = 2;
	public function bets()
    {
        return $this->hasMany('App\CrashBet');
    }
    public function getUbetsAttribute($key)
    {
        $bets = $this->bets();
        foreach ($bets as $key) {
            $ubets[] = [
                'bet' => $key,
                'user' => User::Where('id', $key->user_id)->first()
            ];
        }
        return $ubets;
    }


}
