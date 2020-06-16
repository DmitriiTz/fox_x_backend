<?php

namespace App\Events;

use App\HistoryGame;
use App\Http\Controllers\Account\JackpotController;
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

class CreateGame implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $game;

    public function __construct($game)
    {
        $this->game = $game;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */


    public function broadcastOn()
    {
        if($this->game->game_type_id === 1){
            return new Channel('jackpot-classic');
        }else{
            return new Channel('jackpot');
        }
    }
}
