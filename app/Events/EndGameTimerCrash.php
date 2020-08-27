<?php

namespace App\Events;

use App\CrashGame;
use App\HistoryGame;
use App\Events\EndGameKing;
use App\Http\Controllers\CrashController;
use App\Jobs\CrashTimerJob;
use App\Participant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\DB;

class EndGameTimerCrash implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $timer;

    public function __construct($timer, $bets)
    {
        $this->timer = $timer;
        $this->bets = collect($bets)->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('end-crash-timer');
    }
}
