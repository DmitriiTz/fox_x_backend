<?php

namespace App\Http\Controllers;

use App\Events\CrashTimer;
use App\Jobs\CrashTimerJob;
use App\User;
use App\CrashGame;
use App\CrashBet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Cache;
use App\Events\JoinCrash;
use App\Events\AdmCrash;
use App\Events\CrashCoef;

use Auth;
use App\Payment;

use Illuminate\Support\Facades\Log;
use Storage;
use DB;

class CrashController extends Controller {
	const NEW_BET_CHANNEL = 'betCrash';
	const WINNER_CHANNEL = 'winnerCrash';
	public $game;
	
	public function __construct()
    {
        parent::__construct();
        $this->game = $this->getLastGame();
    }
	
	public function generateSecret(){
  		$chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
  		$numChars = strlen($chars);
  		$string = '';
  		for ($i = 0; $i < 16; $i++) {
    		$string .= substr($chars, rand(1, $numChars) - 1, 1);
  		}
  		return $string;
	}

	public function getLastGame()
    {
        $game = CrashGame::orderBy('id', 'desc')->first();
		$i_int = time() - $game->create_game;

		return response()->json(['i_int' => $i_int, 'curent' => time(), 'n' => $game->number, 'stop_game' => $game->stop_game, 'start_game' => $game->create_game]);
    }

	public function newGame()
    {
    	$game = CrashGame::orderBy('id', 'desc')->first();

    	$stop = $game->stop_game - 10;

    	if($stop < time() OR $game->status == 1){

    		$i = 1;
			$x_int = rand(1, 1000);
			$x_float = rand(10, 100);
			$x = $x_int . '.' . $x_float;
			$y = 1;

			while($i <= 1000){
				$y = $y * 1.06;
				if($y >= $x){
					break;
				}
				$i++;
			}
			if($y > 1000) $y = 1000;
			CrashGame::create([
				'number' => $i,
				'create_game' => time() + 10,
				'rand_number' => '0',
				'profit' => $y,
				'stop_game' => time() + $i + 27
			]);

			$game = CrashGame::orderBy('id', 'desc')->first();

			$adm = time() - $game->create_game;
			AdmCrash::dispatch($adm);

			return "success";

    	}else{
    		return "error";
    	}
    }
	public function setProfit(Request $request)
    {
//        $game_id = $request->game;
        $profit = $request->profit;
        if($profit >= 1000) $profit = 1000;
//    	$game = CrashGame::find($game_id);
        $game = CrashGame::orderBy('id', 'desc')->first();
        if($profit <= $game->rand_number) return false;

        $game->profit = $profit;
    	$game->save();
//        Log::warning('profit ' . print_r($profit,1));
//        Log::warning('req ' . print_r($request,1));
//
//        CrashGame::create([
//            'number' => $i,
//            'create_game' => time() + 10,
//            'profit' => $y,
//            'stop_game' => time() + $i + 27
//        ]);




    }
	public function setCurrentProfit(Request $request)
    {
        $profit = $request->profit;
        if($profit >= 1000) $profit = 1000;
//        Log::warning('profit 1 ' . print_r($profit,1));
        $game = CrashGame::orderBy('id', 'desc')->first();
        $game->profit = $profit;
    	$game->save();
//        Log::warning('profit set ' . print_r($profit,1));
    }
	public function getInfo(Request $request)
    {
        $rand_number = $request->rand_number;
        $admin = $request->admin;

        $game = CrashGame::orderBy('id', 'desc')->first();
        if($game->stop_game < time() && $game->status == 2) {
            $game->status = 3;
            $game->rand_number = $game->profit;
            $game->save();
            self::newGame();
        }
        elseif($game->stop_game > time()) {
            $game->status = 2;
            $game->save();
        }
        else {
            $game->status = 0;
            $game->save();
        }

        if($admin) {
            $bets = DB::table('crashbets')->where('crash_game_id', $game->id)->orderBy('price', 'desc')->get();
            if($game->status == 0) {
                $info = $game;
                $mode = 0;
            }
            else {
                $info = $game;
                $mode = 1;

                $x = time() - $game->create_game;
                $profit = $x;
            }
            return view('admin.crash_result', compact( 'info', 'bets', 'mode'));
        }

        if ($rand_number > $game->rand_number || $game->status == 2) {
            $plus = 0;
            if($rand_number > 1) $plus = 0.2;
            if($rand_number > 2) $plus = 0.5;
            if($rand_number > 5) $plus = 1;
            if($rand_number > 10) $plus = 1.5;
            if($rand_number > 15) $plus = 2;
            if($rand_number > 20) $plus = 3;
            if($rand_number > 30) $plus = 3.5;
            if($rand_number > 40) $plus = 4;
            if($rand_number > 50) $plus = 4.5;
            if($rand_number > 60) $plus = 5;
            if($rand_number > 70) $plus = 6;
            if($rand_number > 80) $plus = 8;
            if($rand_number > 100) $plus = 10;
            if($rand_number > 150) $plus = 15;
            if($rand_number > 200) $plus = 20;
            if($rand_number > 250) $plus = 25;
            if($rand_number > 300) $plus = 30;
            if($rand_number > 350) $plus = 35;
            if($rand_number > 400) $plus = 40;
            if($rand_number > 500) $plus = 50;
            if($rand_number > 600) $plus = 60;
            if($rand_number > 700) $plus = 70;
            if($rand_number > 800) $plus = 80;
            if($rand_number > 900) $plus = 80;
            $rand_number = $rand_number + $plus;
            if($rand_number <= $game->profit) {
                $game->rand_number = $rand_number;
            }
            elseif($game->status == 3) {
                $game->rand_number = $game->profit;
            }
//            $game->rand_number = 0;
            $game->save();
        }


        $bets = DB::table('crashbets')->where('crash_game_id', $game->id)->get();
        $price = 0;
        $ubets = array();
        foreach ($bets as $key) {
            $price = $price + $key->price;
            $ubets[] = [
                'bet' => $key,
                'user' => User::Where('id', $key->user_id)->first()
            ];
        }
        $result['ubets'] = view('crash-ubets', compact('ubets'))->render();
        $result['game'] = $game->toArray();
        return response()->json($result);
    }

    public function crashCoef(Request $request) {
	    $coef = $request->get('coef');
	    event(
	        new CrashCoef($coef)
        );
    }

	public function setGameStatus(Request $request)
    {
        $this->game->status = $request->get('status');
        $this->game->save();
        return $this->game;
    }

	public function index(){

		$pageName = 'Crash';

        $game = CrashGame::orderBy('id', 'desc')->first();

        $games = array();

		$_games = CrashGame::Where('stop_game', '<', time())->orderBy('id', 'desc')->take(20)->get();
		foreach ($_games as $value) {
			$bt = DB::table('crashbets')->where('crash_game_id', $value->id)->get();

			$games[] = [
				'game' => $value,
				'bets' => $bt
			];
		}

		$bets = null;

		if($game->stop_game < time() OR $game->status == 1){

			$i = 1;
			$x_int = rand(1, 100);
			$x_float = rand(10, 10);
			$x = $x_int . '.' . $x_float;
			$y = 1;

			while($i <= 1000){
				$y = $y * 1.06;
				if($y >= $x){
					break;
				}
				$i++;
			}

			$time = $i + 17;
			$hash = hash('sha224', strval($y));

			$game = CrashGame::create([
                'number' => $i,
                'create_game' => time(),
                'profit' => $y,
                'stop_game' => time() + $time,
                'hash' => $hash,
                'link_hash' => 'http://sha224.net/?val='.$hash
            ]);

			$adm = time() - $game->create_game;
			AdmCrash::dispatch($adm);

            for ($j = 0; $j <= $time; $j++) {
                $coef = $i * ($j / ($time));
                $job = (new CrashTimerJob($game->id, $j, $coef))->delay($j);
                $this->dispatch($job);
            }
		}

		$bets = DB::table('crashbets')->where('crash_game_id', $game->id)->get();
		$price = 0;

		$ubets = array();

		foreach ($bets as $key) {
			$price = $price + $key->price;

			$ubets[] = [
				'bet' => $key,
				'user' => User::Where('id', $key->user_id)->first()
			];
		}

		//var_dump(getBalance());

        //return view('crash', compact('pageName', 'game','bets', 'games', 'price', 'ubets'));

        $data = [
            'pageName' => $pageName,
            'game' => $game,
            'bets' => $bets,
            'games' => $games,
            'price' => $price,
            'ubets' => $ubets,
        ];
		return response()->json($data);
	}

	public function info(){

		$games = CrashGame::Where('stop_game', '<', time())->orderBy('id', 'desc')->take(20)->get();

		$content = "";

		foreach ($games as $gf) {

            if($gf->profit < 3){
                $color = 1;
            }else if($gf->profit > 3 AND $gf->profit < 4){
                $color = 2;
            }else if($gf->profit > 4 AND $gf->profit < 100){
                $color = 3;
            }else if($gf->profit > 100 AND $gf->profit < 200){
                $color = 4;
            }else if($gf->profit > 200){
                $color = 5;
            }

            
            $content .= '<li class="color-' . $color .'">';
            $content .= '<a href="javascript:void(0);" data-info="' . $gf->profit . '">' . $gf->profit . '</a>';
            $content .= '</li>';

		}

		return $content;

	}

	public function newBet(Request $request)
    {
        $cashout = $request->get('cashout');
        $bet = $request->get('bet');

        $user = Auth::user();
        $game = CrashGame::orderBy('id', 'desc')->first();
//        $game_id = $game->id;

        $next_id = $game->id + 1;
        $bet_exist = CrashBet::where('user_id', '=', $user->id)->where('crash_game_id', '=', $game->id)->exists();
        $bet_next_exist = CrashBet::where('user_id', '=', $user->id)->where('crash_game_id', '=', $next_id)->exists();

        $game_id = $game->id;
        if(!$bet_exist && $game->rand_number == 0) {
            $game_id = $game->id;
            $status = 1;
            $bets = DB::table('crashbets')->where('crash_game_id', $game_id)->get();
            $price = 0;
            $ubets = array();
            foreach ($bets as $key) {
                $price = $price + $key->price;
                $ubets[] = [
                    'bet' => $key,
                    'user' => User::Where('id', $key->user_id)->first()
                ];
            }
            $result['ubets'] = view('crash-ubets', compact('ubets'))->render();
            $result['msg'] = 'Ваша ставка на текущую игру успешно принята!';
        }
        elseif(!$bet_next_exist) {
            $game_id = $next_id;
            $status = 2;
            $result['msg'] = 'Ставка с суммой ' . $bet . ' руб. и коофициентом ' . $cashout . ' принята для следующей игры №' . $game_id;
        }
        else {
            $game_id = 0;
            $status = 0;
            $result['msg'] = 'Вы уже сделали ставку';
        }

        if($status != 0) {
            DB::table('crashbets')->insert([
                'user_id' => $user->id,
                'number' => $cashout,
                'crash_game_id' => $game_id,
                'price' => $bet
            ]);
            DB::table('payments')->insert([
                'account_id' => Auth::user()->id,
                'price' => '-'.$bet / 10
            ]);
            JoinCrash::dispatch($user, $request->get('cashout'), $request->get('bet'));
        }

        $result['bet'] = $bet;
        $result['status'] = $status;
        return response()->json($result);
    }

    public function updateBalace(Request $request)
    {
    	DB::table('payments')->insert([
			'account_id' => $request->get('id'),
			'price' => $request->get('price') / 10
		]);

    	return response()->json(['id' => $request->get('id'), 'price' => $request->get('price')]);
    }

    public function algorithm() {
        $users = [
            [
                'x' => 500,
                'k' => 6,
            ],
            [
                'x' => 100,
                'k' => 1.25,
            ],
            [
                'x' => 10,
                'k' => 10,
            ],
            [
                'x' => 100,
                'k' => 5,
            ],
        ];

        $tz = $maxtz = $arrx =  $arrk = $arrz = array();
        $sx = $sz = $sk = 0;
        foreach($users as $user => $v) {
            $z = $v['x'] * $v['k'];
            $arrx[] = $v['x'];
            $arrk[] = $v['k'];
            $arrz[] = $z;
            $sx = $sx + $v['x'];
            $sk = $sk + $v['k'];
            $sz = $sz + $z;
            $maxtk[] = $v['k'];
            $maxtz[] = $z;
            print  $user . ' = ' . $v['x'] . ' | ' . $v['k'] . ' | ' . $z . '<br>';
        }
        print '----------<br>';

// $tk = ($sx - ($sx * 0.3));
        sort($arrk);
        $mink = $arrk[0];
        $maxk = $arrk[count($arrk) - 1];
        $c = rand($maxk - 0.001, $mink);
        return $c;
//        print_r($c);
//        print '<br>----------<br>';
//        print_r($tz);
    }
}
