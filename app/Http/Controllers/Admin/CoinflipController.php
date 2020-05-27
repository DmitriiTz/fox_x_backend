<?php

namespace App\Http\Controllers\Account;

use App\Events\CreateGameCoinFlip;
use App\Events\StartGameCoinflip;
use App\HistoryGame;
use App\Jobs\EndGameCoinflip;
use App\Participant;
use App\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CoinflipController extends Controller
{

    public function createGame(Request $request) {

        $user = auth()->user();
        $balance = getBalance($user);

        if($request->cash < 100) {
            return ['error' => 1, 'message' => 'Минимальная ставка - 100 COINS'];
        }

        if($request->cash > $balance) {
            return ['error' => 1, 'message' => 'Недостаточно средств на балансе'];
        }

        $color = 0;
        if($request->color) {
            if($request->color == 'orange') {
                $color = 1;
            }
        }

        
        $game = HistoryGame::whereNull('create_account_id')->where('game_id', 4)->first();
        $game->create_account_id = $user->id;
        $game->save();
        

        $newGame = new HistoryGame;
        $newGame->game_id = 4;
        $newGame->animation_at = now()->addYear();
        $random = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
        $newGame->winner_ticket_big = $random;
        $newGame->winner_ticket = 100 * $random;
        $newGame->hash = hash('sha224', strval($newGame->winner_ticket_big));
        $newGame->link_hash = 'http://sha224.net/?val='.$newGame->winner_ticket_big;
        $newGame->save();

        $participant = new Participant;
        $participant->account_id = $user->id;
        $participant->cash = $request->cash;
        $participant->history_game_id = $game->id;
        $participant->color = $color;

        if(!$color) {
            $participant->min_cash_number = 51;
            $participant->max_cash_number = 100;
        }
        else {
            $participant->min_cash_number = 1;
            $participant->max_cash_number = 50;
        }

        $participant->save();

        $payment = new Payment;
        $payment->account_id = $user->id;
        $payment->price = -$request->cash / 10;
        $payment->payment_type_id = 6;
        $payment->history_game_id = $game->id;
        $payment->game_id = 4;
        $payment->save();

        $view = view('blocks.coinflip-game', compact('game'))->render();

        $hashGame =  hash('sha224', $game->id);
        $hashWinner = hash('sha224', $game->winner_ticket_big);
        $link_hash = 'http://sha224.net/?val='.$hashWinner;
        $winnerTicket = $game->winner_ticket_big;
        $viewPopup = view('popups.wait-player', compact('hashGame', 'link_hash', 'game', 'hashWinner', 'winnerTicket'))->render();



        event(new CreateGameCoinFlip($view));

        $userGames = HistoryGame::where('create_account_id', auth()->user()->id)->count();
        $bankUser = Payment::where('game_id', 4)->where('account_id', auth()->user()->id)
                ->where('created_at', '>', today())
                ->where('created_at', '<', now())
                ->where('price', '>', 0)
                ->sum('price') * 10;

        return ['error' => 0, 'message' => 'Игра успешно создана', 'balance' => getBalance($user), 'view' => $viewPopup, 'userGames' => $userGames, 'bankUser' => $bankUser];

    }

    public function showGameCoinflip(Request $request) {

        $game = HistoryGame::find($request->gameId);

        if($game->end_game_at) {
            $winnerApplication = Participant::where('min_cash_number', '<=', $game->winner_ticket)
                ->where('max_cash_number', '>=', $game->winner_ticket)
                ->where('history_game_id', $game->id)
                ->first();
        }


        $hashGame =  hash('sha224', $game->id);
        $hashWinner = hash('sha224', strval($game->winner_ticket_big));
        $link_hash = 'http://sha224.net/?val='.$game->winner_ticket_big;
        $winnerTicket = $game->winner_ticket_big;
        $viewPopup = view('popups.wait-player', compact('hashGame', 'link_hash', 'game', 'winnerApplication', 'hashWinner', 'winnerTicket'))->render();

        return $viewPopup;

    }

    public function setParticipantCoinflip(Request $request) {


        $game = HistoryGame::find($request->gameId);
        if($game->participants()->count() >= 2) {
            return ['error' => 1, 'message' => 'Игра уже началась'];
        }

        $user = auth()->user();

        $needCash = $game->participants()->first()->cash;


        $participant = new Participant;
        $participant->account_id = $user->id;
        $participant->cash = $needCash;
        $participant->history_game_id = $game->id;
        $participant->color = ($game->participants()->first()->color == '1') ? '0' : '1';

        if($participant->color == '0') {
            $participant->min_cash_number = 51;
            $participant->max_cash_number = 100;
        }
        else {
            $participant->min_cash_number = 1;
            $participant->max_cash_number = 50;
        }

        $participant->save();

        $payment = new Payment;
        $payment->account_id = $user->id;
        $payment->price = -$needCash / 10;
        $payment->payment_type_id = 6;
        $payment->history_game_id = $game->id;
        $payment->game_id = 4;
        $payment->save();

        $balanceUser = getBalance($user);

        $winnerApplication = Participant::where('min_cash_number', '<=', $game->winner_ticket)
            ->where('max_cash_number', '>=', $game->winner_ticket)
            ->where('history_game_id', $game->id)
            ->first();

        // начисление денег победителю и комиссия
        $game->winner_account_id = $winnerApplication->account_id;
        $game->end_game_at = now()->addSeconds(20);
        $game->save();

        $result = commission($game);
        $payment = new Payment;
        $payment->account_id = $winnerApplication->account_id;
        $payment->price = ($game->participants()->sum('cash') / 10) - $result;
        $payment->payment_type_id = 7;
        $payment->history_game_id = $game->id;
        $payment->game_id = 4;
        $payment->save();

        $balance = getBalance($winnerApplication->account);
        $accountId = $winnerApplication->account->id;

        $job = (new EndGameCoinflip($game->id, $game->end_game_at, $game->winner->name, $winnerApplication->color, $winnerApplication->cash, $balance, $accountId))->onConnection( env('QUEUE_CONNECTION_2','redis') )->delay(23);
        $this->dispatch($job);


        $hashGame =  hash('sha224', $game->id);
        $hashWinner = hash('sha224', strval($game->winner_ticket_big));
        $link_hash = 'http://sha224.net/?val='.$game->winner_ticket_big;
        $game->participants = $game->participants()->get();
        $winnerTicket = $game->winner_ticket_big;
        $viewPopup = view('popups.wait-player', compact('hashGame', 'link_hash', 'game', 'winnerApplication', 'hashWinner', 'winnerTicket'))->render();



        $view = view('blocks.coinflip-game', compact('game'))->render();
        event(new StartGameCoinflip($view, $game->id, $viewPopup, $game->winner->name, $winnerApplication->color, $winnerApplication->cash));

        return ['error' => 0, 'message' => $viewPopup, 'balanceUser' => $balanceUser];

    }


}
