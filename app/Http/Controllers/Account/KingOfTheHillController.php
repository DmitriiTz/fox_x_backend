<?php

namespace App\Http\Controllers\Account;

use App\Events\AddParticipantKing;
use App\Events\StartGameKing;
use App\Jobs\StartGameKingJob;
use App\HistoryGame;
use App\Jobs\EndGameKing;
use App\Jobs\EndBettingGameKing;
use App\Participant;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KingOfTheHillController extends Controller
{

    public function setParticipant(Request $request) {

        $currentStepPrice = $this->redis->get('step.' . $request->type);
        
        $user = auth()->user();
        $balance = getBalance($user);

        $type = false;

        if($request->type == 'classic') {
            $type = 3;
        }

        $cash = false;
        if($request->type == 'senyor') {
            $type = 4;
            $cash = $request->cash;

        }

        if($type) {
            $game = HistoryGame::orderBy('updated_at', 'desc')
                ->where('game_id', 2)
                ->where('game_type_id', $type)
                ->where('end_game_at', '>', now())
                ->first();

            info($game->id.'- номер игры -'. now());

            if(!$game) {
                if($balance < 10) {
                    return ['error' => 1, 'message' => 'Недостаточно на балансе'];
                }

                $game = new HistoryGame;
                $game->game_id = 2;
                $game->game_type_id = $type;
                $game->end_game_at = now()->addYear();
                $game->save();

                $currentStepPrice = 0;

                if($type == 3 && $balance >= $currentStepPrice + 10) {
                    $currentBank = $this->createParticipant($user, $game, $currentStepPrice + 10, $type);

                }
                elseif($type == 4 && $cash && $balance >= $cash) {
                    $currentBank = $this->createParticipant($user, $game, $cash, $type);
                }
                else {
                    return ['error' => 1, 'message' => 'Непредвиденная ошибка'];
                }


                $balance = getBalance($user);
                return ['error' => 0, 'message' => 'Ставка успешно сделана', 'balance' => $balance, 'bank' => $currentBank, 'type' => $type];


            }
            else {

                if($game->end_game_at && $game->end_game_at < now()) {
                    return ['error' => 1, 'message' => 'Игра закончена'];
                }

               // $currentStepPrice = Participant::where('history_game_id', $game->id)->orderBy('created_at', 'desc')->first();

                if(!$currentStepPrice) {
                    $currentStepPrice = 0;
                }
               

                info($type.'- сумма '.$cash.'- баланс '.$balance.'- шаг '.$currentStepPrice.'- номер игры'. $game->id);
                if($type == 3 && $balance >= $currentStepPrice + 10) {
                    $currentBank = $this->createParticipant($user, $game, $currentStepPrice + 10, $type);
                }
                elseif($type == 4 && $cash && $balance >= $cash && $cash > $currentStepPrice) {
                    $currentBank = $this->createParticipant($user, $game, $cash, $type);
                }
                else {
                    return ['error' => 1, 'message' => 'Неккоректная сумма'];
                }


                $countParticipants = $game->participants->groupBy('account_id')->count();
                if($countParticipants >= 2) {
                    $timer = now();
                    $end_game_at = $timer->addSeconds(21);
                    $game->end_game_at = $end_game_at;
                    unset($game->participants);
                    $game->save();
//                        EndGameKing::dispatch($game->id, $end_game_at, $type)->delay(20);
              //      $job = (new EndGameKing($game->id, $end_game_at, $type))->onConnection( env('QUEUE_CONNECTION_2','redis') )->delay(22);
                 //   $this->dispatch($job);
                  //  $job2 = (new EndBettingGameKing($game->id, $end_game_at, $type))->onConnection( env('QUEUE_CONNECTION_2','redis') )->delay(20);
                  //  $this->dispatch($job2);
                        for($i = 21,$j=0;$i >= 0,$j <= 21; $i--,$j++)
                        {
                        $job = (new StartGameKingJob($game->id, $i, $type,$end_game_at->timestamp))->onConnection( env('QUEUE_CONNECTION_2','redis') )->delay($j);
                        $this->dispatch($job);
                        }
                   // event(new StartGameKing($game->id, 20, $type,$end_game_at));
                }

                $balance = getBalance($user);

                return ['error' => 0, 'message' => 'Ставка успешно сделана', 'balance' => $balance, 'bank' => $currentBank, 'type' => $type];

            }

        }
        else {
            return ['error' => 1, 'message' => 'Непредвиденная ошибка'];
        }

    }

    public function createParticipant($user, $game, $cash, $type) {

        if($type == 3)
        {
            $this->redis->set('step.classic', $cash);
        }
        else
        {
            $this->redis->set('step.senyor', $cash);
        }
        $participant = new Participant;
        $participant->account_id = $user->id;
        $participant->cash = $cash;
        $participant->history_game_id = $game->id;
        $participant->save();

        $payment = new Payment;
        $payment->account_id = $user->id;
        $payment->price = -$cash / 10;
        $payment->payment_type_id = 6;
        $payment->history_game_id = $game->id;
        $payment->save();

        $listParticipants = $game->participants()->get();
        $game->participants = $listParticipants;


        $bank = $listParticipants->sum('cash');

        $step = count($listParticipants) * 10;

        if($type == 4) {
            $step = Participant::where('history_game_id', $game->id)->orderBy('cash', 'desc')->first()->cash;
        }

        $countParticipants = count($listParticipants);

        $participants = Participant::where('history_game_id', $game->id)->orderBy('created_at', 'desc')->get();

        if(isset($participants[1])) {
            $participant = $participants[1];
            $view = view('blocks.king-participants', compact('participant'))->render();
        }
        else {
            $view = '';
        }


        $image = asset($user->image);

        event(new AddParticipantKing($game->id, $bank, $view, $image, $type, $step, $countParticipants));

        return $bank;
    }



}
