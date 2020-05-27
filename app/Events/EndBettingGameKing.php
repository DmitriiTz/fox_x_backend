<?php

namespace App\Events;

use App\HistoryGame;
use App\Participant;
use App\Payment;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EndBettingGameKing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $gameId;
    public $endGameAt;
    public $type;

    public function __construct($gameId, $endGameAt, $type)
    {
        $this->gameId = $gameId;
        $this->endGameAt = $endGameAt;
        $this->type = $type;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        $game = HistoryGame::find($this->gameId);

        if(!$game->is_view) {
            info('EndBettingGameKing - '. $this->gameId);

            return new PresenceChannel('online-users');

        }

        
    }
}
