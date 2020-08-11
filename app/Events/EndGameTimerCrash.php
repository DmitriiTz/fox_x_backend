<?php

namespace App\Events;

use App\CrashGame;
use App\HistoryGame;
use App\Events\EndGameKing;
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

            $game = CrashGame::orderBy('id', 'desc')->first();
            //$bets = DB::table('crashbets')->where('crash_game_id', $game->id)->get();
            $currentCoef = $game->profit;

//            if($bets){
//                $currentCoef = $this->crash_algorithm($bets);
//                $game->profit = $currentCoef;
//                $game->save();
//            }

//            for ($i = 1, $j = 0; $i <= $currentCoef; $i += $speed, $j++) {
//
//                if ($currentCoef >= 1) $speed = 0.2;
//                if ($currentCoef > 2) $speed = 0.5;
//                if ($currentCoef > 5) $speed = 1;
//                if ($currentCoef > 10) $speed = 1.5;
//                if ($currentCoef > 15) $speed = 2;
//                if ($currentCoef > 20) $speed = 3;
//                if ($currentCoef > 30) $speed = 3.5;
//                if ($currentCoef > 40) $speed = 4;
//                if ($currentCoef > 50) $speed = 4.5;
//                if ($currentCoef > 60) $speed = 5;
//                if ($currentCoef > 70) $speed = 6;
//                if ($currentCoef > 80) $speed = 8;
//                if ($currentCoef > 100) $speed = 10;
//                if ($currentCoef > 150) $speed = 15;
//                if ($currentCoef > 200) $speed = 20;
//                if ($currentCoef > 250) $speed = 25;
//                if ($currentCoef > 300) $speed = 30;
//                if ($currentCoef > 350) $speed = 35;
//                if ($currentCoef > 400) $speed = 40;
//                if ($currentCoef > 500) $speed = 50;
//                if ($currentCoef > 600) $speed = 60;
//                if ($currentCoef > 700) $speed = 70;
//                if ($currentCoef > 800) $speed = 80;
//                if ($currentCoef > 900) $speed = 80;
//
//                $job = (new CrashTimerJob($game->id, $i, $currentCoef, $speed))->delay($j);
//                $this->dispatch($job);
//            }

            $game = CrashGame::where('status', 1)->first();
            $game->update(['status' => 2]);
        }
        return new Channel('end-crash-timer');
    }

    function crash_algorithm($bets)
    {
        $raw_data = $bets;
        $raw_data = $raw_data->map(function ($elem) {
            $elem->number = $elem->number ? $elem->number : 100;
            return ['p' => $elem->user_id, 'x' => $elem->price, 'k' => $elem->number, 'z' => $elem->price * $elem->number];
        });
        $sum_bet = $raw_data->sum('x');
        $owner_k = 0.3;
        $total_money_p = $sum_bet * (1 - $owner_k);
        $game_data_z = $raw_data->sortBy('z');
        $game_data_k = collect([]);
        while ($game_data_z->isNotEmpty() && $game_data_z->sum('z') >= $total_money_p) {
            $game_data_k->push($game_data_z->pop());
        }
        $max_z = $game_data_z->max('k');
        $min_k = $game_data_k->min('k');
        if($max_z > $min_k)
        {
            $coef = $max_z + 0.01 + ((double)rand())/(getrandmax())*($min_k - $max_z - 0.01);
        }
        else if ($max_z == 1.1 || $min_k == 1.1)
        {
            $coef = 1;
        }
        else
        {
            $coef = $min_k - 0.01 - ((double)rand())/(getrandmax());
        }
        return $coef;
    }
}
