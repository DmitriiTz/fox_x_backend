<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrashBet extends Model
{
	protected $table = 'crashbets';
	protected $fillable = ['user_id','number','crash_game_id','price'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function game()
    {
        return $this->belongsTo('App\CrashGame');
    }
}
