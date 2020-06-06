<?php

namespace App\Events;

use App\HistoryGame;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StartGameCoinflip implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $gameId;
    public $participant;
    public $cash;

//    public $game;
//    public $gameId;
//    public $data_popup;
//    public $winnerName, $color, $cash, $allCash, $allGame, $allGameWait;

    public function __construct($gameId, $participant, $cash)
    {
        $this->gameId = $gameId;
        $this->participants = $participant;
        $this->cash = $cash;

//        $this->game = $game;
//        $this->gameId = $gameId;
//        $this->data_popup = $data_popup;
//        $this->winnerName = $winnerName;
//        $this->color = $color;
//        $this->cash = $cash;
//
//        $coins = HistoryGame::orderBy('created_at', 'desc')
//            ->with(['participants'])
//            ->where('game_id', 4)
//            ->get();
//
//        $bank = 0;
//        foreach ($coins as $coin) {
//            $bank += $coin->participants->sum('cash');
//        }
//
//        $this->allCash = $bank;
//        $this->allGame = HistoryGame::where('game_id', 4)->where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->count();
//        $this->allGameWait = HistoryGame::orderBy('created_at', 'desc')
//            ->with(['winner', 'participants'])
//            ->where('game_id', 4)
//            ->whereNull('end_game_at')
//            ->count();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('coin-flip');
    }
}
