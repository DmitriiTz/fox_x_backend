<?php

namespace App\Events;

use App\CrashGame;
use App\Jobs\CrashTimerJob;
use App\Jobs\EndCrashTimerJob;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateGameCrash implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $game;

    public function __construct($number, $create_game, $profit, $stop_game, $hash, $link_hash)
    {
        dump(['Enter Create game']);

        $this->game = CrashGame::create([
            'number' => $number,
            'create_game' => $create_game,
            'profit' => $profit,
            'stop_game' => $stop_game,
            'hash' => $hash,
            'link_hash' => $link_hash
        ]);

//        $adm = time() - $this->create_game;
//        AdmCrash::dispatch($adm);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        dump('create Crash');
        return new Channel('crash');
    }
}
