<?php

namespace App\Events;

use App\CrashBet;
use App\CrashGame;
use App\HistoryGame;
use App\Http\Controllers\CrashController;
use App\Jobs\EndCrashTimerJob;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\DB;

class CrashTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameId;
    public $endGameAt;
    public $coef;
    public $endTimer;
    public $payments;

    public function __construct($gameId, $end_game_at, $coef, $endTimer)
    {

        $this->gameId = $gameId;
        $this->endGameAt = $end_game_at;
        $this->coef = $coef;
        $this->endTimer = $endTimer;
        $this->payments;
    }

    public function broadcastOn()
    {
        $game = CrashGame::find($this->gameId);
        $game->profit = 1.06 ** $this->coef;
        $game->save();

        if($game->profit >= $game->stop_game){
            $main = new MainController;
            $main->crash_stop();
        }

        if ($game->status != 3 && $this->endGameAt == $this->endTimer) {
            //Artisan::call('queue:clear', ['connection' => 'redis']);
            $bets = CrashBet::where(['crash_game_id' => $game->id])->get();
            foreach ($bets as $bet) {
                if($bet->number < $game->profit){
                    DB::table('payments')->insert([
                        'account_id' => $bet->user_id,
                        'price' => $bet->price * $bet->number / 10
                    ]);
                }
            }

            $create_game = new CrashController();
            $create_game->createGame();

            $game->update(['status' => 3]);
        }
        return new Channel('crash-timer');
    }
}
