<?php

namespace App\Events;

use App\CrashBet;
use App\CrashGame;
use App\HistoryGame;
use App\Http\Controllers\CrashController;
use App\Jobs\EndCrashTimerJob;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\DB;

class StartGameCoinFlipTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timer;
    public $game_id;

    public function __construct($game_id, $timer)
    {
        $this->game_id = $game_id;
        $this->timer = $timer;
    }

    public function broadcastOn()
    {
        return new Channel('coin-flip_'. $this->game_id);
    }
}
