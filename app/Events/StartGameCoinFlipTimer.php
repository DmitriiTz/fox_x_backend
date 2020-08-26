<?php

namespace App\Events;

use App\HistoryGame;
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

        $game = HistoryGame::find($game_id);
        $game->duration = $timer;
        $game->save();

        $this->timer = $timer;
    }

    public function broadcastOn()
    {
        return new Channel('coin-flip_'. $this->game_id);
    }
}
