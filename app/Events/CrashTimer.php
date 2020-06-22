<?php

namespace App\Events;

use App\Participant;
use App\User;
use App\HistoryGame;
use App\Events\EndGameKing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CrashTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $gameId;
    public $endGameAt;

    public function __construct($gameId, $end_game_at)
    {

        $this->gameId = $gameId;
        $this->endGameAt = $end_game_at;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('crash-timer');
    }
}
