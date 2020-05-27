<?php

namespace App\Http\Controllers;

use App\GameType;
use App\HistoryGame;
use App\Participant;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class MainController extends Controller
{
    public function home(Request $request)
    {

        $pageName = 'Jackpot';
        $timer = now();
        $time = now();
        $gameTypeId = 1;
        $typeRequest = 'get';

        if ($request->isMethod('post')) {
            $gameTypeId = $request->typeGameId;
            $typeRequest = 'post';
        }


        $gameType = GameType::find($gameTypeId);
        $game = HistoryGame::with([
            'participants' => function ($query) {
                $query->with('account');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->where('game_id', 3)
            ->where('animation_at', '>', Carbon::now())
            ->where('game_type_id', $gameTypeId)
            ->where('status_id', '!=', 4)
            ->first();

        $result = [];

        if ($game && $game->end_game_at && strtotime($game->end_game_at) < strtotime($time) && $game->winner_account_id) {
            $bank = $game->participants()->sum('cash');
            $cashInBank = Participant::where('history_game_id', $game->id)->where('account_id', $game->winner_account_id)->sum('cash');
            $color = Participant::where('history_game_id', $game->id)->where('account_id', $game->winner_account_id)->first()->color;
            $percent = round($cashInBank * 100 / $bank, 2);
            $historyGame = HistoryGame::with([
                'participants' => function ($query) {
                    $query->with('account');
                }
            ])->where('id', $game->id)->first();
            $listParticipants = $historyGame->participants;
            $result = [
                'winnerTicket' => $game->winner_ticket,
                'linkHash' => $game->link_hash,
                'hash' => $game->hash,
                'percent' => $percent,
                'image' => $game->winner->image,
                'name' => $game->winner->name . ' ' . $game->winner->last_name,
                'bank' => $bank,
                'winners' => view('blocks.choose-winner-slider', ['game' => $historyGame])->render(),
                'color' => $color
            ];

        }

        $games = HistoryGame::orderBy('created_at', 'desc')
            ->with(['winner', 'participants'])
            ->where('game_id', 3)
            ->where('game_type_id', $gameTypeId)
            ->where('animation_at', '<', now())
            ->limit(10)
            ->get();

        foreach ($games as $key => $gameIter) {
            if ($gameIter->winner_ticket) {
                $particIter = Participant::where('min_cash_number', '<=', $gameIter->winner_ticket)
                    ->where('max_cash_number', '>=', $gameIter->winner_ticket)
                    ->where('history_game_id', $gameIter->id)
                    ->first();
                $gameIter->winner->color = $particIter->color;
                $gameIter->winner->range = $particIter->min_cash_number . ' - ' . $particIter->max_cash_number;
            }
        }


        $tempGame = HistoryGame::with([
            'participants' => function ($query) {
                $query->with('account');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->where('game_id', 3)
            ->where('game_type_id', $gameTypeId)
            ->where('status_id', '!=', 4)
            ->where('winner_ticket', '!=', null)
            ->first();
        if (isset($tempGame)) {
            $bank = $tempGame->participants()->sum('cash');
            $cashInBank = Participant::where('history_game_id', $tempGame->id)->where('account_id',
                $tempGame->winner_account_id)->sum('cash');
            $color = Participant::where('history_game_id', $tempGame->id)->where('account_id',
                $tempGame->winner_account_id)->first()->color;
            $percent = round($cashInBank * 100 / $bank, 2);
            $lastWinner = [
                'winnerTicket' => $tempGame->winner_ticket,
                'linkHash' => $tempGame->link_hash,
                'hash' => $tempGame->hash,
                'percent' => $percent,
                'image' => $tempGame->winner->image,
                'name' => $tempGame->winner->name . ' ' . $tempGame->winner->last_name,
                'bank' => $bank,
                'color' => $color
            ];

        } else {
            $lastWinner = [
                'winnerTicket' => '---',
                'linkHash' => '---',
                'hash' => '---',
                'percent' => '---',
                'image' => '/img/fox.png',
                'name' => 'Не определено',
                'bank' => '---',
                'color' => '---'
            ];
        }
		

       $tempGame = HistoryGame::with([
            'participants' => function ($query) {
                $query->with('account');
            }
        ])
			->orderBy('created_at', 'desc')
            ->where('game_id', 3)
            ->where('game_type_id', $gameTypeId)
            ->where('status_id', '!=', 4)
            ->where('winner_ticket', '!=', null)
            ->first();
		
        if (isset($tempGame)) {
            $bank = $tempGame->participants()->sum('cash');
            $cashInBank = Participant::where('history_game_id', $tempGame->id)->where('account_id',
                $tempGame->winner_account_id)->sum('cash');
            $color = Participant::where('history_game_id', $tempGame->id)->where('account_id',
                $tempGame->winner_account_id)->first()->color;
            $percent = round($cashInBank * 100 / $bank, 2);
            $luckyOfTheDay = [
                'winnerTicket' => $tempGame->winner_ticket,
                'linkHash' => $tempGame->link_hash,
                'hash' => $tempGame->hash,
                'percent' => $percent,
                'image' => $tempGame->winner->image,
                'name' => $tempGame->winner->name . ' ' . $tempGame->winner->last_name,
                'bank' => $bank,
                'color' => $color
            ];
        } else {
            $luckyOfTheDay = [
                'winnerTicket' => '---',
                'linkHash' => '---',
                'hash' => '---',
                'percent' => '---',
                'image' => '/img/fox.png',
                'name' => 'Не определено',
                'bank' => '---',
                'color' => '---'
            ];
        }
		
		
		

        $max_cash_number = Participant::max('max_cash_number');
        $biggestBank = Participant::where('max_cash_number', '=', $max_cash_number)->first();

		
		
        $tempGame = HistoryGame::with([
            'participants' => function ($query) {
                $query->with('account');
            }
        ])
            ->where('id', $biggestBank->history_game_id)
            ->where('game_id', 3)
            ->where('game_type_id', $gameTypeId)
            ->where('status_id', '!=', 4)
            ->where('winner_ticket', '!=', null)
            ->first();
			
        if (isset($tempGame)) {
            $bank = $tempGame->participants()->sum('cash');
            $cashInBank = Participant::where('history_game_id', $tempGame->id)->where('account_id',
                $tempGame->winner_account_id)->sum('cash');
            $color = Participant::where('history_game_id', $tempGame->id)->where('account_id',
                $tempGame->winner_account_id)->first()->color;
            $percent = round($cashInBank * 100 / $bank, 2);
            $biggestBank = [
                'winnerTicket' => $tempGame->winner_ticket,
                'linkHash' => $tempGame->link_hash,
                'hash' => $tempGame->hash,
                'percent' => $percent,
                'image' => $tempGame->winner->image,
                'name' => $tempGame->winner->name . ' ' . $tempGame->winner->last_name,
                'bank' => $bank,
                'color' => $color
            ];
        } else {
            $biggestBank = [
                'winnerTicket' => '---',
                'linkHash' => '---',
                'hash' => '---',
                'percent' => '---',
                'image' => '/img/fox.png',
                'name' => 'Не определено',
                'bank' => '---',
                'color' => '---'
            ];
        }


        if ($request->isMethod('post')) {
            $view = view('blocks.change-game-wrap-jackpot',
                compact('game', 'games', 'gameTypeId', 'result', 'timer', 'time', 'gameType', 'typeRequest', 'luckyOfTheDay', 'biggestBank',
                    'lastWinner'))->render();
            return $view;
        }

        return view('jackpot',
            compact('game', 'games', 'gameTypeId', 'result', 'timer', 'time', 'gameType', 'typeRequest', 'pageName', 'luckyOfTheDay',
                'biggestBank', 'lastWinner'));
    }

    public function coinflip()
    {
        $pageName = 'Coinflip';
        $games = HistoryGame::orderBy('created_at', 'desc')
            ->with(['winner', 'participants', 'type'])
            ->where('game_id', 4)
            ->where('end_game_at', '<', now()->subSeconds(10))
            ->whereNotNull('create_account_id')
            ->limit(10)
            ->get();


        $activeGames = HistoryGame::orderBy('created_at', 'desc')
            ->with(['winner', 'participants'])
            ->where('game_id', 4)
            ->whereNull('end_game_at')
            ->whereNotNull('create_account_id')
            ->get();

        $animationGames = HistoryGame::orderBy('created_at', 'desc')
            ->with(['winner', 'participants'])
            ->where('game_id', 4)
            ->whereNotNull('end_game_at')
            ->whereNotNull('create_account_id')
            ->where('end_game_at', '>', now())
            ->get();

        $coins = HistoryGame::orderBy('created_at', 'desc')
            ->with(['participants'])
            ->where('game_id', 4)
            ->get();

        $bank = 0;
        foreach ($coins as $coin) {
            $bank += $coin->participants->sum('cash');
        }

        $userGames = false;
        $bankUser = false;

        if (auth()->user()) {
            $userGames = HistoryGame::where('create_account_id', auth()->user()->id)->count();
            $userGamesNow = HistoryGame::where('end_game_at', '=', null)->where('create_account_id', auth()->user()->id)->count();
            $bankUser = Payment::where('game_id', 4)->where('account_id', auth()->user()->id)
                    ->where('created_at', '>', today())
                    ->where('created_at', '<', now())
                    ->where('price', '>', 0)
                    ->sum('price') * 10;
        }

        $gamesToday = HistoryGame::where('game_id', 4)->where('created_at', '>', Carbon::today())->where('created_at', '<',
            Carbon::now())->count();


        return view('coin-flip',
            compact('games', 'activeGames', 'animationGames', 'bank', 'gamesToday', 'userGames', 'bankUser', 'pageName', 'userGamesNow'));
    }

    public function kingOfTheHill()
    {
        $pageName = 'King of the hill';
        $timer = now();

        $games = HistoryGame::orderBy('created_at', 'desc')
            ->with(['winner', 'participants', 'type'])
            ->where('game_id', 2)
            ->where('status_id', '!=', 1)
            ->limit(10)
            ->get();

        $gameClassic = HistoryGame::with([
            'participants' => function ($query) {
                $query->with('account')->orderBy('cash', 'desc');
            }
        ])
            ->orderBy('updated_at', 'desc')
            ->where('game_id', 2)
            ->where('game_type_id', 3)
            ->where('end_game_at', '>', now())
            ->first();

        if (!$gameClassic) {
            $gameClassic = new HistoryGame;
            $gameClassic->game_id = 2;
            $gameClassic->game_type_id = 3;
            $gameClassic->end_game_at = now()->addYear();
            $gameClassic->save();
        }

        $lastGame = HistoryGame::orderBy('created_at', 'desc')->first();


        $gameSenyor = HistoryGame::with([
            'participants' => function ($query) {
                $query->with('account')->orderBy('cash', 'desc');
            }
        ])
            ->orderBy('updated_at', 'desc')
            ->where('game_id', 2)
            ->where('game_type_id', 4)
            ->where('end_game_at', '>', now())
            ->first();

        if (!$gameSenyor) {
            $gameSenyor = new HistoryGame;
            $gameSenyor->game_id = 2;
            $gameSenyor->game_type_id = 4;
            $gameSenyor->end_game_at = now()->addYear();
            $gameSenyor->save();
        }


        return view('king-of-the-hill', compact('games', 'gameClassic', 'gameSenyor', 'timer', 'pageName', 'lastGame'));
    }

    public function crash(){

        $pageName = 'Crash';

        return view('crash', compact('pageName'));
    }

}
