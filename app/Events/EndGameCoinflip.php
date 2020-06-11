<?php

namespace App\Events;

use App\HistoryGame;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EndGameCoinflip implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $game;
    public $bankUser;

    public function __construct($gameId, $bankUser)
    {
        $this->game = HistoryGame::select('id', 'link_hash', 'winner_account_id', 'winner_ticket')->where('id', $gameId)->with('participants.account')->first();

        $this->bankUser = Payment::where('game_id', 4)->where('account_id', auth()->user()->id)
                ->where('created_at', '>', today())
                ->where('created_at', '<', now())
                ->where('price', '>', 0)
                ->sum('price') * 10;
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
