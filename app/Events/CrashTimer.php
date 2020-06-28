<?php

namespace App\Events;

use App\HistoryGame;
use App\Http\Controllers\CrashController;
use App\Jobs\EndCrashTimerJob;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CrashTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameId;
    public $endGameAt;
    public $coef;
    public $endTimer;

    public function __construct($gameId, $end_game_at, $coef, $endTimer)
    {
        $this->gameId = $gameId;
        $this->endGameAt = $end_game_at;
        $this->coef = $coef;
        $this->endTimer = $endTimer;
    }

    public function broadcastOn()
    {
        $game = HistoryGame::find($this->gameId);
        if ($game->status !== 3) {
            if ($this->endGameAt === $this->endTimer) {
                $create_game = new CrashController();
                $create_game->createGame();
            }
        }
        return new Channel('crash-timer');
    }
}
