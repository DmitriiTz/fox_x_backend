<?php

namespace App\Events;

use App\CrashGame;
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
        $game = CrashGame::find($this->gameId);
        $game->profit = 1.06 ** $this->coef;
        $game->save();
        if ($game->status !== 3 && $this->endGameAt === $this->endTimer) {
            $create_game = new CrashController();
            $create_game->createGame();

            $game->update(['status' => 3]);
        }
        return new Channel('crash-timer');
    }
}
