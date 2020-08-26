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

//            $create_game = new CrashController();
//            $create_game->test();

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
