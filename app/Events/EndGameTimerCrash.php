<?php

namespace App\Events;

use App\CrashGame;
use App\HistoryGame;
use App\Events\EndGameKing;
use App\Participant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EndGameTimerCrash implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $timer;

    public function __construct($timer)
    {
        $this->timer = $timer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if($this->timer === 10){
            $game = CrashGame::where('status', 1)->first();
            $game->update(['status' => 2]);
        }
        return new Channel('end-crash-timer');
    }
}
