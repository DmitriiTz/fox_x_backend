<?php

namespace App\Http\Controllers\Account;

use App\Events\AddParticipant;
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

class JackpotController extends Controller
{


    
    public function WinnerTimer($gameId,$hash,$winnerTicket,$linkHash,$timer,$percent,$image,$name,$bank,$winnerId,$viewHistoryWinner,$accountId,$balanceUser,$winners)
    {
        for($i = 14,$j=0;$i >= 0,$j <= 14; $i--,$j++)
                        {
                           
                        $job = (new EndGameTimer($i,$j,$gameId,$hash,$winnerTicket,$linkHash,$timer,$percent,$image,$name,$bank,$winnerId,$viewHistoryWinner,$accountId,$balanceUser,$winners))->onConnection( env('QUEUE_CONNECTION_2','redis') )->delay($j);
                        $this->dispatch($job);
                        }
                        return true;
    }

 

    public function setParticipant(Request $request) {

        if(($request->cash > 300 || (int) $request->cash <= 0) && $request->gameTypeId == 2) {
            return ['error' => 1, 'message' => $request->cash > 300  ? 'Максимальная ставка 300 COINS' : 'Минимальная ставка 1 COIN'];
        }

        if($request->cash < 300 && $request->gameTypeId == 1) {
            return ['error' => 1, 'message' => 'Минимальная ставка 300 COINS'];
        }

        if($request->get('userid') > 0)
        {
            echo 'antihack';
      //      $user = User::where('id', $request->get('userid'))->first();
        }
        else
        {
            $user = auth()->user();
        }
        
        $balance = getBalance($user);

        if($balance < $request->cash) {
            return ['error' => 1, 'message' => 'Недостаточно на балансе'];
        }

        if($request->cash && $request->gameTypeId) {
            $gameType = GameType::where('id', $request->gameTypeId)->where('game_id', 3)->firstOrFail();
            $game = HistoryGame::orderBy('created_at', 'desc')
                ->where('game_id', 3)
//                ->where('status_id', 1)
                ->where('game_type_id', $gameType->id)
                ->where('status_id', '!=', 4)
                ->where('animation_at', '>', Carbon::now())
                ->first();

            if($game && $game->animation_at > Carbon::now() && $game->end_game_at < Carbon::now() && $game->winner_ticket && $game->winner_account_id) {
                return ['error' => 1, 'message' => 'Предыдущая игра еще не закончена'];
            }

        
           
            if(!$game) {

                $game = HistoryGame::where('status_id', 4)->where('game_type_id', $request->gameTypeId)->first();
             
               $game->status_id = 1;
               $game->save();

         
               srand(time() / 5 + 199526178);
                $firstGameBet = Participant::where('account_id', $user->id)->where('history_game_id', $game->id)->first();
                if($firstGameBet)
                {
                    $color = $firstGameBet->color;
                }
                else
                {
                    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                }
                $this->createParticipant($user, $game, $request->cash, $gameType, $color);
                return ['error' => 0, 'message' => 'Ставки принята', 'balance' => getBalance($user)];
            }
            else {
                    $firstGameBet = Participant::where('account_id', $user->id)->where('history_game_id', $game->id)->first();
                if($firstGameBet)
                {
                    $color = $firstGameBet->color;
                }
                else
                {
                    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

                }
                if($game->end_game_at && $game->end_game_at > now()) {
                    $getCountAppication = $this->getCountApplicationAccount($user, $game);
                    if($getCountAppication) {

                        $this->createParticipant($user, $game, $request->cash, $gameType,$color);
                        return ['error' => 0, 'message' => 'Ставки принята', 'balance' => getBalance($user)];
                    }
                    else {
                        return ['error' => 1, 'message' => 'Максимальное количество ставок - 3'];
                    }

                }
                elseif($game->end_game_at && $game->end_game_at < now()) {
                    return ['error' => 1, 'message' => 'Время закончилось для ставок'];
                }

                if(!$game->end_game_at) {
                    $getCountAppication = $this->getCountApplicationAccount($user, $game);
                    if($getCountAppication) {
                        $this->createParticipant($user, $game, $request->cash, $gameType,$color);

                        $countParticipants = $game->participants->groupBy('account_id')->count();

                        if($countParticipants == 2) {
                            $timer = now();
                            $timer2 = now();
                            $end_game_at = $timer->addSeconds(36);
                            unset($game->participants);
                            $game->end_game_at = $end_game_at;
                            $animationAt = $timer2->addSeconds(50);
                            $game->animation_at = $animationAt; //33
                            $game->save();
//                            EndGame::dispatch($game->id, $end_game_at)->delay(20);

                        
                         /*
                          $job = (new EndGame($game->id, $end_game_at))->onConnection( env('QUEUE_CONNECTION_2','redis') )->delay(34);
                            $this->dispatch($job);
                */
                             for($i = 36,$j=0;$i >= 0,$j <= 36; $i--,$j++)
                        {
                        $job = (new StartGameJob($game->id, $end_game_at, $gameType->name,$i))->onConnection( env('QUEUE_CONNECTION_2','redis') )->delay($j);
                        $this->dispatch($job);
                        }
                  
                                             //       event(new StartGame($game->id, $end_game_at, $gameType->name));

                        }

                        return ['error' => 0, 'message' => 'Ставки принята', 'balance' => getBalance($user)];

                    }
                    else {
                        return ['error' => 1, 'message' => 'Максимальное количество ставок - 3'];
                    }

                }

            }

        }

        return ['error' => 1, 'message' => 'Непредвиденная ошибка'];

    }

    public function createParticipant($user, $historyGame, $cash, $gameType,$color) {

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

        $historyGame = HistoryGame::with(['participants' => function($query) {
            $query->with('account');
        }])->where('id',$historyGame->id)->first();
        $listParticipants = $historyGame->participants;
        $bank = $historyGame->participants->sum('cash');
        $winners = view('blocks.choose-winner-slider', ['game' => $historyGame])->render();
        $view = view('blocks.jackpot-participants', ['game' => $historyGame])->render();
        $bet = view('blocks.bet-jackpot', ['bet' => $participant])->render();

        if(count($listParticipants) == 1) {
            $isReload = 1;
        }
        else {
            $isReload = 0;
        }

        $hashGame =  $historyGame->hash;
        event(new AddParticipant($view, $winners,$bet, 'jackpot', $bank, $isReload, count($listParticipants), $historyGame->id, $gameType->name, $hashGame));

    }

    public function getCountApplicationAccount($user, $historyGame) {
        $count = Participant::where('account_id', $user->id)->where('history_game_id', $historyGame->id)->count();
        if($count >= 3) {
            return false;
        }
        return true;
    }

}
