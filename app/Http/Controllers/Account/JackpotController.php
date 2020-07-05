<?php

namespace App\Http\Controllers\Account;

use App\Events\AddParticipant;
use App\Events\CreateGame;
use App\Events\StartGame;
use App\Jobs\StartGameJob;
use App\GameType;
use App\HistoryGame;
use App\Jobs\EndGame;
use App\Jobs\EndGameTimer;
use App\Participant;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JackpotController extends Controller
{
    public function WinnerTimer(
        $gameId,
        $hash,
        $winnerTicket,
        $linkHash,
        $timer,
        $percent,
        $image,
        $name,
        $bank,
        $winnerId,
        $viewHistoryWinner,
        $accountId,
        $balanceUser,
        $winners)
    {
        for ($i = 14, $j = 0; $i >= 0, $j <= 14; $i--, $j++) {

            $job = (
            new EndGameTimer(
                $i,
                $j,
                $gameId,
                $hash,
                $winnerTicket,
                $linkHash,
                $timer,
                $percent,
                $image,
                $name,
                $bank,
                $winnerId,
                $viewHistoryWinner,
                $accountId,
                $balanceUser,
                $winners
            )
            )->delay($j);
            $this->dispatch($job);
        }
        return true;
    }


    public function setParticipant(Request $request)
    {
        if (($request->cash > 300 || (int)$request->cash <= 0) && $request->gameTypeId == 2) {
            return ['error' => 1, 'message' => $request->cash > 300 ? 'Максимальная ставка 300 COINS' : 'Минимальная ставка 1 COIN'];
        }

        if ($request->cash < 300 && $request->gameTypeId == 1) {
            return ['error' => 1, 'message' => 'Минимальная ставка 300 COINS'];
        }

        if ($request->get('userid') > 0) {
            echo 'antihack';
        } else {
            $user = auth()->user();
        }

        $balance = getBalance($user);

        if ($balance < $request->cash) {
            return ['error' => 1, 'message' => 'Недостаточно на балансе'];
        }
        $gameType = GameType::where('id', $request->gameTypeId)->where('game_id', 3)->firstOrFail();
        $game = HistoryGame::orderBy('created_at', 'desc')
            ->where('game_id', 3)
//                ->where('status_id', 1)
            ->where('game_type_id', $gameType->id)
            ->where('status_id', '!=', 4)
            ->where('animation_at', '>', Carbon::now())
            ->first();

        if ($game && $game->animation_at > Carbon::now() && $game->end_game_at < Carbon::now() && $game->winner_ticket && $game->winner_account_id) {
            return ['error' => 1, 'message' => 'Предыдущая игра еще не закончена'];
        }

        if (!$game) {

            $gameBefore = HistoryGame::where('status_id', 0)->where('game_id', 3)->get();
            if($gameBefore->count() < 10)
            {
                while($gameBefore->count() < 10)
                {
                    $newGame = new HistoryGame;
                    $newGame->status_id = 0;
                    $newGame->game_id = 3;
                    $random = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
                    $newGame->winner_ticket_big = $random;
                    $newGame->winner_ticket = 100 * $random;
                    $newGame->hash = hash('sha224', strval($newGame->winner_ticket_big));
                    $newGame->link_hash = 'http://sha224.net/?val='.$newGame->winner_ticket_big;
                    $newGame->save();
                }
            }

            $game = $gameBefore->first();
            $game->status_id = 1;
            $game->game_type_id = $request->gameTypeId;
            $game->animation_at = now()->addYear();
            $game->save();

            event(new CreateGame($game));

            $firstGameBet = Participant::where('account_id', $user->id)->where('history_game_id', $game->id)->first();
            if ($firstGameBet) {
                $color = $firstGameBet->color;
            } else {
                $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            }

            $this->createParticipant($user, $game, $request->cash, $gameType, $color);
            return ['error' => 0, 'message' => 'Ставки принята', 'balance' => getBalance($user)];
        } else {
            $firstGameBet = Participant::where('account_id', $user->id)->where('history_game_id', $game->id)->first();
            if ($firstGameBet) {
                $color = $firstGameBet->color;
            } else {
                $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

            }
            if ($game->end_game_at && $game->end_game_at > now()) {

                $getCountAppication = $this->getCountApplicationAccount($user, $game);
                if ($getCountAppication) {

                    $this->createParticipant($user, $game, $request->cash, $gameType, $color);

                    return ['error' => 0, 'message' => 'Ставки принята', 'balance' => getBalance($user)];
                } else {
                    return ['error' => 1, 'message' => 'Максимальное количество ставок - 3'];
                }

            } elseif ($game->end_game_at && $game->end_game_at < now()) {
                return ['error' => 1, 'message' => 'Время закончилось для ставок'];
            }

            if (!$game->end_game_at) {
                $getCountAppication = $this->getCountApplicationAccount($user, $game);
                if ($getCountAppication) {
                    $this->createParticipant($user, $game, $request->cash, $gameType, $color);

                    $countParticipants = $game->participants->groupBy('account_id')->count();

                    if ($countParticipants == 2) {
                        $timer = now();
                        $timer2 = now();
                        $end_game_at = $timer->addSeconds(34);
                        unset($game->participants);
                        $game->end_game_at = $end_game_at;
                        $animationAt = $timer2->addSeconds(50);
                        $game->animation_at = $animationAt; //33
                        $game->save();

                        for ($i = 34, $j = 0; $i >= 0, $j <= 34; $i--, $j++) {
                            $job = (new StartGameJob($game->id, $end_game_at, $gameType->name, $i))->delay($j);
                            $this->dispatch($job);
                        }
                    }

                    return ['error' => 0, 'message' => 'Ставки принята', 'balance' => getBalance($user)];

                } else {
                    return ['error' => 1, 'message' => 'Максимальное количество ставок - 3'];
                }

            }

        }

        return ['error' => 1, 'message' => 'Непредвиденная ошибка'];

    }

    public function createParticipant($user, $historyGame, $cash, $gameType, $color)
    {

        $bank = +$historyGame->participants()->sum('cash');
        $participant = new Participant;
        $participant->account_id = $user->id;
        $participant->cash = $cash;
        $participant->history_game_id = $historyGame->id;

        $participant->min_cash_number = $bank + 1;
        $participant->max_cash_number = $bank + $cash;
        $participant->color = $color;

        $participant->save();

        $payment = new Payment;
        $payment->account_id = $user->id;
        $payment->price = -$cash / 10;
        $payment->payment_type_id = 6;
        $payment->history_game_id = $historyGame->id;
        $payment->save();

        $historyGame = HistoryGame::with(['participants' => function ($query) {
            $query->with('account');
        }])->where('id', $historyGame->id)->first();
        $listParticipants = $historyGame->participants;
        $bank = $historyGame->participants->sum('cash');
        //$winners = view('blocks.choose-winner-slider', ['game' => $historyGame])->render();
        //$view = view('blocks.jackpot-participants', ['game' => $historyGame])->render();

        //$bet = view('blocks.bet-jackpot', ['bet' => $participant])->render();

        if (count($listParticipants) == 1) {
            $isReload = 1;
        } else {
            $isReload = 0;
        }

        $hashGame = $historyGame->hash;
        event(new AddParticipant($historyGame, $historyGame, $participant, 'jackpot', $bank, $isReload, count($listParticipants), $historyGame->id, $gameType->name, $hashGame));

    }

    public function getCountApplicationAccount($user, $historyGame)
    {
        $count = Participant::where('account_id', $user->id)->where('history_game_id', $historyGame->id)->count();
        if ($count >= 3) {
            return false;
        }
        return true;
    }

}
