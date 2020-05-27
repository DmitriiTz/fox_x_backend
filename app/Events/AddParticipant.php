<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddParticipant implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $view;
    public $type;
    public $bank;
    public $isReload;
    public $winners;
    public $countParticipants;
    public $gameId;
    public $gameTypeName;
    public $hashGame;
    public $bet;

    public function __construct($view, $winners, $bet, $type, $bank, $isReload, $countParticipants, $gameId, $gameTypeName, $hashGame)
    {
        $this->view = $view;
        $this->type = $type;
        $this->bet = $bet;
        $this->bank = $bank;
        $this->isReload = $isReload;
        $this->winners = $winners;
        $this->countParticipants = $countParticipants;
        $this->gameId = $gameId;
        $this->gameTypeName = $gameTypeName;
        $this->hashGame = $hashGame;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('online-users');
    }
}
