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

class AdminCrashTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameId;
    public $timer;
    public $alpha;
    public $coef;
    public $profit;
    public $game;
    public function __construct($gameId, $timer, $alpha, $coef, $profit, $game)
    {
        $this->gameId = $gameId;
        $this->timer = $timer;
        $this->alpha = $alpha;
        $this->coef = $coef;
        $this->profit = $profit;
        $this->game = $game;
    }

    public function broadcastOn()
    {
        return new Channel('admin-crash-timer');
    }
}
