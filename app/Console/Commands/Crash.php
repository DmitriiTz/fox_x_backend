<?php

namespace App\Console\Commands;

use App\CrashBet;
use App\CrashGame;
use App\Events\CrashTimer;
use App\Events\CreateGame;
use App\Events\CreateGameCrash;
use App\Events\EndGameCrash;
use App\Events\EndGameTimerCrash;

use App\Game;
use App\Http\Controllers\CrashController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use function random_int;

class Crash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crash:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start crash game';

    private $current_game;
    private $current_alpha;
    private $current_profit;
    private $current_end_time;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function timer()
    {
        //$crash = new CrashController();
        // $crash->index();

        //задержка надписи crashed
        sleep(2);

        for ($z = 0; $z <= 10; $z++) {
            time_nanosleep(0, (int)10e7);
            $this->info($z);
            event(new EndGameTimerCrash($z));
        }
    }

    private function crashAlgorithm($bets)
    {
        $raw_data = collect($bets);
        $raw_data = $raw_data->map(function ($elem) {
            $elem['number'] = $elem['number'] ? $elem['number'] : 100;
            return ['p' => $elem['user_id'], 'x' => $elem['price'], 'k' => $elem['number'], 'z' => $elem['price'] * $elem['number']];
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
        else if ($max_z <= 1.1 || $min_k <= 1.1)
        {
            $coef = 1;
        }
        else
        {
            $coef = $min_k - 0.01 - ((double)rand())/(getrandmax());
        }
        return max($coef, 1);
    }

    private function createGame()
    {
        if (Cache::has('next_crash_coefficient')) {
            $x = Cache::get('next_crash_coefficient');
            Cache::forget('next_crash_coefficient');
        } else {
            $bets = CrashBet::query()->where(['crash_game_id' => $this->current_game->id+1])->get();
            $x = 1+rand()/getrandmax()*10;
        }
        try {
            $i = random_int(50, 100);
        } catch (\Exception $e) {
            $i = 3;
        }
        $this->current_alpha = $i/log($x, 2);
        $this->current_profit = $x;
        $this->current_end_time = $i;
        $this->current_game = CrashGame::query()->create([
            'number' => $i,
            'create_game' => time(),
            'rand_number' => '0',
            'profit' => $x,
            'stop_game' => time() + $i
        ]);
        event($event = new CreateGameCrash($this->current_game->id));
        $hash = hash('sha224', strval($x));
        $link_hash = 'http://sha224.net/?val=' . $hash;
    }

    private function game()
    {
        $this->info('starting game...');

        for ($timer = 0; $timer <= $this->current_end_time-1; $timer++) {
            sleep(1);
            $coef = pow(2,$timer/$this->current_alpha);
            $this->info($timer . ' - ' . $this->current_end_time.' coef:'.$coef);
            event(new CrashTimer($this->current_game->id, $timer, $this->current_alpha,$coef));
        }
        if($this->current_game)
            $this->current_game->update(['status' => 3]);
    }

    private function endGame()
    {
        event(new EndGameCrash($this->current_game));
        $bets = CrashBet::query()->where(['crash_game_id' => $this->current_game->id])->get();
        foreach ($bets as $bet) {
            $commission = new \App\Commission;
            $commission->game_id = 9;
            $commission->price = ($bet->price / 10) * 10 / 100;
            $commission->save();
            if ($bet->number < $this->current_game->profit) {
                DB::table('payments')->insert([
                    'account_id' => $bet->user_id,
                    'price' => $bet->price * $bet->number / 10
                ]);
            }
        }
        $this->info('Game crashed');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->current_game = CrashGame::query()->orderBy('id','desc')->first();
        $this->current_game->update(['status'=>3]);
        while (true) {
            $this->createGame();
            $this->timer();
            $this->game();
            $this->endGame();
        }
    }
}
