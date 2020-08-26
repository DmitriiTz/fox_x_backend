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

class CrashTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameId;
    public $timer;
    public $alpha;
    public $coef;

    public function __construct($gameId, $timer, $alpha, $coef)
    {
        $this->gameId = $gameId;
        $this->timer = $timer;
        $this->alpha = $alpha;
        $this->coef = $coef;
    }

    public function broadcastOn()
    {
        return new Channel('crash-timer');
    }
}
