<?php

namespace App\Events;

use App\HistoryGame;
use App\Events\EndGameKing;
use App\Participant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EndGameTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $game;
    public $range;
    public $timer;
    public $gameId;
    public $hash;
    public $winnerTicket;
    public $linkHash;
    public $time;
    public $percent;
    public $image;
    public $name;
    public $bank;
    public $winnerId;
    public $viewHistoryWinner;
    public $accountId;
    public $balanceUser;
    public $winners;

    public function __construct($mainTimer, $gameId, $hash, $winnerTicket, $linkHash, $timer, $percent, $image, $name, $bank, $winnerId, $viewHistoryWinner, $accountId, $balanceUser, $winners)
    {
        $this->game = HistoryGame::find($gameId);
        $this->gameId = $gameId;
        $this->hash = $hash;
        $this->winnerTicket = $winnerTicket;
        $this->linkHash = $linkHash;
        $this->time = $timer;
        $this->percent = $percent;
        $this->image = $image;
        $this->name = $name;
        $this->bank = $bank;
        $this->winnerId = $winnerId;
        $this->viewHistoryWinner = $viewHistoryWinner;

        $particIter = Participant::where('min_cash_number', '<=', $this->game->winner_ticket)
            ->where('max_cash_number', '>=', $this->game->winner_ticket)
            ->where('history_game_id', $this->game->id)
            ->first();
        $this->viewHistoryWinner->winner->range = $particIter->min_cash_number . ' - ' . $particIter->max_cash_number;
        $this->range = $particIter->min_cash_number . ' - ' . $particIter->max_cash_number;

        $this->accountId = $accountId;
        $this->balanceUser = $balanceUser;
        $this->winners = $winners;
        $this->timer = $mainTimer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //   info('timer - '. ($this->endGameTime - now()->timestamp));
        if($this->game->game_type_id === 1){
            return new Channel('jackpot-classic');
        }
        else{
            return new Channel('jackpot');
        }

    }
}
