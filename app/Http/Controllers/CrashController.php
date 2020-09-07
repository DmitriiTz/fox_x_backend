<?php

namespace App\Http\Controllers;

use App\Events\CreateGameCrash;
use App\Events\FlashOutCrash;
use App\Jobs\CrashTimerJob;
use App\Jobs\EndCrashTimerJob;
use App\User;
use App\CrashGame;
use App\CrashBet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Events\JoinCrash;
use App\Events\AdmCrash;
use App\Events\CrashCoef;
use Auth;
use Illuminate\Support\Facades\Redis;
use Storage;
use Illuminate\Support\Facades\DB;

class CrashController extends Controller
{
    const NEW_BET_CHANNEL = 'betCrash';
    const WINNER_CHANNEL = 'winnerCrash';
    public $game;

    public function __construct()
    {
        parent::__construct();
        $this->game = $this->getLastGame();
    }

    function crash_algorithm($bets)
    {
        $raw_data = collect($bets);
        $raw_data = $raw_data->map(function ($elem) {
            $elem['coef'] = $elem['coef'] ? $elem['coef'] : 100;
            return ['p' => $elem['user_id'], 'x' => $elem['bet'], 'k' => $elem['coef'], 'z' => $elem['bet'] * $elem['coef']];
        });
        $sum_bet = $raw_data->sum('x');
        $owner_k = 0.3;
        $total_money_p = $sum_bet * (1 - $owner_k);
        $game_data_z = $raw_data->sortBy('z');
        $game_data_k = collect([]);
        while ($game_data_z->isNotEmpty() && $game_data_z->sum('z') >= $total_money_p) {
            $game_data_k->push($game_data_z->pop());
        }
        $max_z = $game_data_z->max('k');
        $min_k = $game_data_k->min('k');
        if($max_z > $min_k)
        {
            $coef = $max_z + 0.01 + ((double)rand())/(getrandmax())*($min_k - $max_z - 0.01);
        }
        else if ($max_z == 1.1 || $min_k == 1.1)
        {
            $coef = 1;
        }
        else
        {
            $coef = $min_k - 0.01 - ((double)rand())/(getrandmax());
        }
        return $coef;
    }

    public function test()
    {
        $game = CrashGame::orderBy('id', 'desc')->first();
        $bets = DB::table('crashbets')->where('crash_game_id', $game->id)->get();
        $currentCoef = $game->profit;

        if ($bets) {
            $currentCoef = $this->crash_algorithm($bets);
            $game->profit = $currentCoef;
            $game->save();
        }

        for ($i = 1, $j = 0; $i <= $currentCoef; $i += $speed, $j++) {
            if ($i >= 1) $speed = 0.2;
            if ($i > 2) $speed = 0.5;
            if ($i > 5) $speed = 1;
            if ($i > 10) $speed = 1.5;
            if ($i > 15) $speed = 2;
            if ($i > 20) $speed = 3;
            if ($i > 30) $speed = 3.5;
            if ($i > 40) $speed = 4;
            if ($i > 50) $speed = 4.5;
            if ($i > 60) $speed = 5;
            if ($i > 70) $speed = 6;
            if ($i > 80) $speed = 8;
            if ($i > 100) $speed = 10;
            if ($i > 150) $speed = 15;
            if ($i > 200) $speed = 20;
            if ($i > 250) $speed = 25;
            if ($i > 300) $speed = 30;
            if ($i > 350) $speed = 35;
            if ($i > 400) $speed = 40;
            if ($i > 500) $speed = 50;
            if ($i > 600) $speed = 60;
            if ($i > 700) $speed = 70;
            if ($i > 800) $speed = 80;
            if ($i > 900) $speed = 80;

            $job = (new CrashTimerJob($game->id, $i, $currentCoef, $speed))->delay($j);
            $this->dispatch($job);

            $game = CrashGame::where('status', 1)->first();
            $game->update(['status' => 2]);
        }
    }

    public function generateSecret()
    {
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

        if ($stop < time() or $game->status == 1) {

            $i = 1;
            $x_int = rand(1, 1000);
            $x_float = rand(10, 100);
            $x = $x_int . '.' . $x_float;
            $y = 1;

            while ($i <= 1000) {
                $y = $y * 1.06;
                if ($y >= $x) {
                    break;
                }
                $i++;
            }
            if ($y > 1000) $y = 1000;
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

        } else {
            return "error";
        }
    }

    public function setProfit(Request $request)
    {
        $profit = $request->profit;
        Cache::put('next_crash_coefficient', $profit, 60);

        //dd($y_cache = Cache::get('next_crash_coefficient'));
        return true;

//        $profit = $request->profit;
//        if ($profit >= 1000) $profit = 1000;
//        $game = CrashGame::orderBy('id', 'desc')->first();
//        if ($profit <= $game->rand_number) return false;
//
//        $game->profit = $profit;
//        $game->save();
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
        if ($profit >= 1000) $profit = 1000;
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
        if ($game->stop_game < time() && $game->status == 2) {
            $game->status = 3;
            $game->rand_number = $game->profit;
            $game->save();
            self::newGame();
        } elseif ($game->stop_game > time()) {
            $game->status = 2;
            $game->save();
        } else {
            $game->status = 0;
            $game->save();
        }

        if ($admin) {
            $bets = DB::table('crashbets')->where('crash_game_id', $game->id)->orderBy('price', 'desc')->get();
            if ($game->status == 0) {
                $info = $game;
                $mode = 0;
            } else {
                $info = $game;
                $mode = 1;

                $x = time() - $game->create_game;
                $profit = $x;
            }
            return view('admin.crash_result', compact('info', 'bets', 'mode'));
        }

        if ($rand_number > $game->rand_number || $game->status == 2) {
            $plus = 0;
            if ($rand_number > 1) $plus = 0.2;
            if ($rand_number > 2) $plus = 0.5;
            if ($rand_number > 5) $plus = 1;
            if ($rand_number > 10) $plus = 1.5;
            if ($rand_number > 15) $plus = 2;
            if ($rand_number > 20) $plus = 3;
            if ($rand_number > 30) $plus = 3.5;
            if ($rand_number > 40) $plus = 4;
            if ($rand_number > 50) $plus = 4.5;
            if ($rand_number > 60) $plus = 5;
            if ($rand_number > 70) $plus = 6;
            if ($rand_number > 80) $plus = 8;
            if ($rand_number > 100) $plus = 10;
            if ($rand_number > 150) $plus = 15;
            if ($rand_number > 200) $plus = 20;
            if ($rand_number > 250) $plus = 25;
            if ($rand_number > 300) $plus = 30;
            if ($rand_number > 350) $plus = 35;
            if ($rand_number > 400) $plus = 40;
            if ($rand_number > 500) $plus = 50;
            if ($rand_number > 600) $plus = 60;
            if ($rand_number > 700) $plus = 70;
            if ($rand_number > 800) $plus = 80;
            if ($rand_number > 900) $plus = 80;
            $rand_number = $rand_number + $plus;
            if ($rand_number <= $game->profit) {
                $game->rand_number = $rand_number;
            } elseif ($game->status == 3) {
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

    public function crashCoef(Request $request)
    {
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

    public function createGame()
    {
        return true;
        //$gameBefore = CrashGame::where('status', 0)->limit(100)->get();

        if (Cache::has('next_crash_coefficient')) {
            $y_cache = Cache::get('next_crash_coefficient');
            Cache::forget('next_crash_coefficient');

            $i = 1;
            $y = 1;
            while ($i <= 1000) {
                $y = $y * 1.06;
                if ($y >= $y_cache) {
                    break;
                }
                $i++;
            }
        } else {
            $x_int = rand(1, 9);
            $x_float = rand(1, 100);
            $x = $x_int . '.' . $x_float;
            //$x = floor((mt_srand(time())/mt_getrandmax())*10)/10+1;

            $i = 1;
            $y = 1;
            while ($i <= 1000) {
                $y = $y * 1.06;
                if ($y >= $x) {
                    break;
                }
                $i++;
            }
        }

        $time = $i + 17;
        $hash = hash('sha224', strval($y));
        $link_hash = 'http://sha224.net/?val=' . $hash;

        event(new CreateGameCrash($i, time(), $y, time() + $time + 10, $hash, $link_hash));

        for ($z = 0; $z <= 10; $z++) {
            $job = (new EndCrashTimerJob($z, $time, $i))->delay($z + 2);
            $this->dispatch($job);
        }

        $game = CrashGame::orderBy('id', 'desc')->first();

        for ($j = 0; $j <= $time; $j++) {
            $coef = $i * ($j / ($time));
            $job = (new CrashTimerJob($game->id, $j, $coef, $time))->delay($j + 10 + 2);
            $this->dispatch($job);
        }

        return true;
    }

    public function index()
    {
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

        if ($game->stop_game < time() or $game->status == 3) {
            $this->createGame();
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

        $game = $game->toArray();

        unset($game['profit']);

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

    public function info()
    {

        $games = CrashGame::Where('stop_game', '<', time())->orderBy('id', 'desc')->take(20)->get();

        $content = "";

        foreach ($games as $gf) {

            if ($gf->profit < 3) {
                $color = 1;
            } else if ($gf->profit > 3 and $gf->profit < 4) {
                $color = 2;
            } else if ($gf->profit > 4 and $gf->profit < 100) {
                $color = 3;
            } else if ($gf->profit > 100 and $gf->profit < 200) {
                $color = 4;
            } else if ($gf->profit > 200) {
                $color = 5;
            }


            $content .= '<li class="color-' . $color . '">';
            $content .= '<a href="javascript:void(0);" data-info="' . $gf->profit . '">' . $gf->profit . '</a>';
            $content .= '</li>';

        }

        return $content;

    }

    public function flashCashOut()
    {
        $user = Auth::user();
        $game = CrashGame::orderBy('id', 'desc')->first();
        $bet = CrashBet::where(['user_id' => $user->id, 'crash_game_id' => $game->id])->first();
        $number = $this->redis->get('crash');
        if ($bet->number < 1 || $bet->number >= $number) {
            $bet->number = $number;
            $bet->save();
        }

        event(new FlashOutCrash($bet));

        return response()->json(['status' => 1, 'msg' => 'Вы вышли на ' . $bet->number . ' коэффициентe']);
    }

    public function cashout()
    {
        $user = Auth::user();
        $game = CrashGame::orderBy('id', 'desc')->first();
        $bet = CrashBet::where(['user_id' => $user->id, 'crash_game_id' => $game->id])->first();
        if ($game->status === 2) {
            DB::table('payments')->insert([
                'account_id' => $user->id,
                'price' => $bet->price / 10
            ]);
            return response()->json(['id' => $user->id, 'price' => $bet->price]);
        }

        return response()->json(['status' => 0, 'error' => 'Игра уже закончена']);
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
        if (!$bet_exist && $game->rand_number == 0 && $game->status === 1) {
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
            //$result['ubets'] = view('crash-ubets', compact('ubets'))->render();
            $result['ubets'] = $ubets;
            $result['msg'] = 'Ваша ставка на текущую игру успешно принята!';
        } elseif (!$bet_next_exist) {
            $game_id = $next_id;
            $status = 2;
            if ($cashout === '0.00') {
                $result['msg'] = 'Вы сделали ставку на следущую игру с произвольным коэффициентом';
            } else {
                $result['msg'] = 'Ставка с суммой ' . $bet . ' и коофициентом ' . $cashout . ' принята для следующей игры №' . $game_id;
            }

        } else {
            $game_id = 0;
            $status = 0;
            $result['msg'] = 'Вы уже сделали ставку';
        }

        if ($status !== 0) {
            DB::table('crashbets')->insert([
                'user_id' => $user->id,
                'number' => $cashout,
                'crash_game_id' => $game_id,
                'price' => $bet
            ]);
            DB::table('payments')->insert([
                'account_id' => Auth::user()->id,
                'price' => '-' . $bet / 10
            ]);

            if ($status !== 2) {
                JoinCrash::dispatch($user, $request->get('cashout'), $request->get('bet'));
            }
        }

        $result['bet'] = $bet;
        $result['status'] = $status;
        return response()->json($result);
    }

    //исправленный алгоритм
    public function check_algorithm(){
        $game = CrashGame::orderBy('id', 'desc')->first();
        $bets = \Illuminate\Support\Facades\DB::table('crashbets')->where('crash_game_id', $game->id)->get();

        $raw_data = $bets;
        $raw_data = $raw_data->map(function ($elem) {
            $elem->number = $elem->number ? $elem->number : 100;
            return ['p' => $elem->user_id, 'x' => $elem->price, 'k' => $elem->number, 'z' => $elem->price * $elem->number];
        });
        $sum_bet = $raw_data->sum('x');
        $owner_k = 0.3;
        $total_money_p = $sum_bet * (1 - $owner_k);
        $game_data_z = $raw_data->sortBy('z');
        $game_data_k = collect([]);
        while ($game_data_z->isNotEmpty() && $game_data_z->sum('z') >= $total_money_p) {
            $game_data_k->push($game_data_z->pop());
        }
        $max_z = $game_data_z->max('k');
        $min_k = $game_data_k->min('k');
        if($max_z > $min_k)
        {
            $coef = $max_z + 0.01 + ((double)rand())/(getrandmax())*($min_k - $max_z - 0.01);
        }
        else if ($max_z == 1.1 || $min_k == 1.1)
        {
            $coef = 1;
        }
        else
        {
            $coef = $min_k - 0.01 - ((double)rand())/(getrandmax());
        }
//        $response = [
//            'coef' => $coef,
//            'bets' => $bets
//        ];
        return $coef;
    }

    public function algorithm()
    {
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

        $tz = $maxtz = $arrx = $arrk = $arrz = array();
        $sx = $sz = $sk = 0;
        foreach ($users as $user => $v) {
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
