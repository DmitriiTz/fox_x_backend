<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddParticipantKing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $bank;
    public $view;
    public $image;
    public $type;
    public $gameId;
    public $step;
    public $countParticipants;

    public function __construct($gameId,$bank, $view, $image, $type, $step, $countParticipants)
    {
        $this->bank = $bank;
        $this->view = $view;
        $this->image = $image;
        $this->type = $type;
        $this->gameId = $gameId;
        $this->step = $step;
        $this->countParticipants = $countParticipants;
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
