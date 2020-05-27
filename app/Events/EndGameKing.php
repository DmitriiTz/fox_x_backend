<?php

namespace App\Events;

use App\HistoryGame;
use App\Participant;
use App\Payment;
use App\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EndGameKing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $gameId;
    public $endGameAt;
    public $type;
    public $view;
    public $newGameId;
    public $winnerAccountId;
    public $winnerTicket;
    public $balance;
    public $winner;
    public $temp;

    public function __construct($gameId, $endGameAt, $type)
    {
        $redis = Redis::connection();
        if($type == 3)
        {
            $redis->set('step.classic', 0);
        }
        else
        {
            $redis->set('step.senyor', 0);
        }   
        
        $this->gameId = $gameId;
        $this->endGameAt = $endGameAt;
        $this->type = $type;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        
        $game = HistoryGame::find($this->gameId);

        if(!$game->is_view) {
            $winner = Participant::where('history_game_id', $this->gameId)->orderBy('created_at', 'desc')->first();
            $game->winner_account_id = $winner->account_id;
            $game->status_id = 2;
            $game->is_view = 1;
            $game->save();
            $winner->save();

            $user = User::find($winner->account_id);

            $result = commission($game);
            $payment = new Payment;
            $payment->account_id = $winner->account_id;
            $payment->price = ($game->participants()->sum('cash') / 10) - $result;
            $payment->payment_type_id = 7;
            $payment->history_game_id = $game->id;
            $payment->save();

            $this->view = view('blocks.history-king', compact('game'))->render();

            $newGame = new HistoryGame;
            $newGame->game_type_id = $this->type;
            $newGame->game_id = 2;
            $newGame->end_game_at = now()->addYear();
            $newGame->save();
            $this->newGameId = $newGame->id;
            $this->balance = getBalance($user);
            $this->winnerAccountId = $winner->account_id;
            $user->price = Participant::where('history_game_id', $this->gameId)->where('account_id', $winner->account_id)->sum('cash');
            $this->winner = $user;
            $this->temp = 'temp';
            $this->winnerTicket = $game->participants()->sum('cash');
            info('я сюда захожу - '. $this->newGameId);

            return new PresenceChannel('online-users');

        }

        
    }
}
