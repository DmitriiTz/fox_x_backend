<?php

namespace App\Events;

use App\CrashGame;
use App\Jobs\CrashTimerJob;
use App\Jobs\EndCrashTimerJob;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\DB;

class CreateGameCrash implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $game;
    public $ubets;
    public function __construct($number, $create_game, $profit, $stop_game, $hash, $link_hash)
    {
        $this->game = CrashGame::create([
            'number' => $number,
            'create_game' => $create_game,
            'status' => 1,
            'profit' => $profit,
            'stop_game' => $stop_game,
            'hash' => $hash,
            'link_hash' => $link_hash
        ]);
        $bets = DB::table('crashbets')->where('crash_game_id', $this->game->id)->get();
        $this->ubets = array();
        foreach ($bets as $key) {
            $this->ubets[] = [
                'bet' => $key,
                'user' => User::Where('id', $key->user_id)->first()
            ];
        }

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
        return new Channel('crash');
    }
}
