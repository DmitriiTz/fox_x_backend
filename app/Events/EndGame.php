<?php

namespace App\Events;

use App\HistoryGame;
use App\Http\Controllers\Account\JackpotController;
use App\Participant;
use App\Payment;
use App\User;
use Carbon\Carbon;
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

    public $game;
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
        //dump(['gameId' => $gameId]);
        $game = HistoryGame::whereId($gameId)->with('participants')->first();
        $this->game = $game;
        //dd($game);
        $this->gameId = $gameId;
        //$game = HistoryGame::with('participants')->where('id', $this->gameId)->first();

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
        $this->viewHistoryWinner = $gameIteration;

//        $gameBefore = new \App\HistoryGame();
//        $gameBefore->game_id = 3;
//        $gameBefore->game_type_id = $game->game_type_id;
//
//        $random = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
//        $gameBefore->winner_ticket_big = $random;
//
//        $gameBefore->hash = hash('sha224', strval($gameBefore->winner_ticket_big));
//        $gameBefore->link_hash = 'http://sha224.net/?val='.$game->hash;
//        $gameBefore->status_id = 4;
//        $gameBefore->animation_at = now()->addYear();
//        $gameBefore->save();

        $game = HistoryGame::with(['participants' => function($query) {
            $query->with('account');
        }])->where('id',$game->id)->first();
        $listParticipants = $game->participants;

        //$this->winners = view('blocks.choose-winner-slider', ['game' => $game])->render();
        $this->winners = $game;
        $gameTypeId = $game->game_type_id;
        $new_game = HistoryGame::query()->with(['participants'])
            ->orderBy('id', 'asc')
            ->where('game_id', 3)
            ->where('animation_at', '>', Carbon::now())
            ->where('game_type_id', $gameTypeId)
            ->whereNotIn('status_id', [4, 0])
            ->first();

        $result = [];
        if (!$new_game) {
            $gameBefore = HistoryGame::where('status_id', 0)->where('game_id', 3)->limit(100)->get();
            if ($gameBefore->count() < 10) {
                for ($i = 0; $i < 10; $i++)
                {
                    $new_game = new HistoryGame;
                    $new_game->game_id = 3;
                    $new_game->status_id = 0;
                    $random = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
                    $new_game->winner_ticket_big = $random;
                    $new_game->animation_at = now()->addYear();
                    $new_game->hash = hash('sha224', strval($new_game->winner_ticket_big));
                    $new_game->link_hash = 'http://sha224.net/?val=' . $new_game->hash;
                    $new_game->save();
                }
            }
            $new_game = HistoryGame::query()->with(['participants'])
                ->orderBy('id', 'asc')
                ->where('game_id', 3)
                ->first();
            $new_game->status_id = 1;
            $new_game->game_type_id = $gameTypeId;
            $new_game->animation_at = now()->addYear();
            $new_game->save();
            event(new CreateGame($new_game));
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */


    public function broadcastOn()
    {
        if($this->game->game_type_id === 1){
            (new JackpotController())->WinnerTimer(
                $this->gameId,
                $this->hash,
                $this->winnerTicket,
                $this->linkHash,
                $this->timer,
                $this->percent,
                $this->image,
                $this->name,
                $this->bank,
                $this->winnerId,
                $this->viewHistoryWinner,
                $this->accountId,
                $this->balanceUser,
                $this->winners);
            return new Channel('jackpot-classic');
        }else{
            (new JackpotController())->WinnerTimer(
                $this->gameId,
                $this->hash,
                $this->winnerTicket,
                $this->linkHash,
                $this->timer,
                $this->percent,
                $this->image,
                $this->name,
                $this->bank,
                $this->winnerId,
                $this->viewHistoryWinner,
                $this->accountId,
                $this->balanceUser,
                $this->winners);
            return new Channel('jackpot');
        }
    }
}
