<?php

namespace App\Events;

use App\HistoryGame;
use App\Http\Controllers\Account\JackpotController;
use App\Participant;
use App\Payment;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EndGame implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $gameId;
    public $hash;
    public $winnerTicket;
    public $linkHash;
    public $timer;
    public $percent;
    public $image;
    public $name;
    public $bank;
    public $winnerId;
    public $viewHistoryWinner;
    public $accountId;
    public $balanceUser;
    public $winners;

    public function __construct($gameId, $hash = false, $winnerTicket = false, $linkHash = false, $timer = false, $percent = false, $image = false, $name = false, $bank = false, $winnerId = false, $winners = false)
    {
        $this->gameId = $gameId;
        $game = HistoryGame::with('participants')->where('id', $this->gameId)->first();
        $bank = $game->participants()->sum('cash');

        $winnerTicket = round($bank * $game->winner_ticket_big);
        $game->winner_ticket = $winnerTicket;
        $winnerApplication = Participant::where('min_cash_number', '<=', $winnerTicket)
            ->where('max_cash_number', '>=', $winnerTicket)
            ->where('history_game_id', $game->id)
            ->first();

        $cashInBank = Participant::where('history_game_id', $game->id)->where('account_id', $winnerApplication->account_id)->sum('cash');

        $account = User::find($winnerApplication->account_id);
        $game->winner_account_id = $account->id;
        $game->chance = round($cashInBank * 100 / $bank, 2);
        $game->save();

        $result = commission($game);
        $payment = new Payment;
        $payment->account_id = $account->id;
        $payment->price = ($bank / 10) - $result;
        $payment->payment_type_id = 7;
        $payment->history_game_id = $game->id;
        $payment->save();

        $this->accountId = $account->id;
        $this->balanceUser = getBalance($account);

        $this->hash = $game->winner_ticket_big;
        $this->winnerTicket = $game->winner_ticket;
        $this->linkHash = 'http://sha224.net/?val='.$game->winner_ticket_big;
        $this->timer = 15;
        $this->percent = round($cashInBank * 100 / $bank, 2);
        $this->image = asset($account->image);
        $this->name = $account->name.' '.$account->last_name;
        $this->bank = $bank;
        $this->winnerId = $account->id;
        $gameIteration = HistoryGame::where('id', $this->gameId)->with(['winner', 'participants'])->first();
        $this->viewHistoryWinner = view('blocks.history-jackpot', compact('gameIteration'))->render();

    


        $gameBefore = new \App\HistoryGame();
        $gameBefore->game_id = 3;
        $gameBefore->game_type_id = $game->game_type_id;

        $random = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
        $gameBefore->winner_ticket_big = $random;

        $gameBefore->hash = hash('sha224', strval($gameBefore->winner_ticket_big));
        $gameBefore->link_hash = 'http://sha224.net/?val='.$game->hash;
        $gameBefore->status_id = 4;
        $gameBefore->animation_at = now()->addYear();
        $gameBefore->save();

        $game = HistoryGame::with(['participants' => function($query) {
            $query->with('account');
        }])->where('id',$game->id)->first();
        $listParticipants = $game->participants;

        $this->winners = view('blocks.choose-winner-slider', ['game' => $game])->render();

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */


    public function broadcastOn()
    {
        (new JackpotController())->WinnerTimer($this->gameId,$this->hash,$this->winnerTicket,$this->linkHash,$this->timer,$this->percent,$this->image,$this->name,$this->bank,$this->winnerId,$this->viewHistoryWinner,$this->accountId,$this->balanceUser,$this->winners);
        return new PresenceChannel('online-users');
    }
}
