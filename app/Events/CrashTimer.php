<?php

namespace App\Events;

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

    public function __construct($gameId, $end_game_at)
    {
        $this->gameId = $gameId;
        $this->endGameAt = $end_game_at;
    }

    public function broadcastOn()
    {
        return new Channel('crash-timer');
    }
}
