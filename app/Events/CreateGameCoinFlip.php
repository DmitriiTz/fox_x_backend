<?php

namespace App\Events;

use App\HistoryGame;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateGameCoinFlip implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $view, $allCash, $allGame, $allGameWait;

    public function __construct($view)
    {
        $this->view = $view;

        $coins = HistoryGame::orderBy('created_at', 'desc')
            ->with(['participants'])
            ->where('game_id', 4)
            ->get();

        $bank = 0;
        foreach ($coins as $coin) {
            $bank += $coin->participants->sum('cash');
        }

        $this->allCash = $bank;
        $this->allGame = HistoryGame::where('game_id', 4)->where('created_at', '>', Carbon::today())->where('created_at', '<', Carbon::now())->count();
        $this->allGameWait = HistoryGame::orderBy('created_at', 'desc')
            ->with(['winner', 'participants'])
            ->where('game_id', 4)
            ->whereNull('end_game_at')
            ->count();
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
