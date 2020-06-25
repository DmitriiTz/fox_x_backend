<?php

namespace App\Events;

use App\CrashGame;
use App\Jobs\CrashTimerJob;
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

    public function __construct()
    {
        $i = 1;
        $x_int = rand(1, 10);
        $x_float = rand(10, 1);
        $x = $x_int . '.' . $x_float;
        $y = 1;

        while($i <= 1000){
            $y = $y * 1.06;
            if($y >= $x){
                break;
            }
            $i++;
        }

        $time = $i + 17;
        $hash = hash('sha224', strval($y));

        $game = CrashGame::create([
            'number' => $i,
            'create_game' => time(),
            'profit' => $y,
            'stop_game' => time() + $time + 10,
            'hash' => $hash,
            'link_hash' => 'http://sha224.net/?val='.$hash
        ]);

        $adm = time() - $game->create_game;
        AdmCrash::dispatch($adm);

        for ($j = 0; $j <= $time; $j++) {
            $coef = $i * ($j / ($time));
            $job = (new CrashTimerJob($game->id, $j, $coef, $time))->delay($j + 10);
            $this->dispatch($job);
        }
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
