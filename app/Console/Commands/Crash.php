<?php

namespace App\Console\Commands;

use App\CrashGame;
use App\Events\CrashTimer;
use App\Events\CreateGameCrash;
use App\Events\EndGameTimerCrash;
use App\Http\Controllers\CrashController;
use App\Http\Controllers\MainController;
use App\Jobs\CrashTimerJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $crash = new CrashController();
        $crash->index();

        //задержка надписи crashed
        sleep(2);

        for ($z = 0; $z <= 10; $z++) {
            sleep(1);
            $this->info($z);

            event(new EndGameTimerCrash($z));
        }
    }

    private function game()
    {
        $this->info('starting game...');

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

        $game = CrashGame::orderBy('id', 'desc')->first();
        //$game->update(['status' => 2]);

        for ($j = 0; $j <= $time; $j++) {
            sleep(1);
            $this->info($j .' - '. $time);

            $coef = $i * ($j / ($time));
            event(new CrashTimer($game->id, $j, $coef, $time));
        }

        $game->update(['status' => 3]);
    }
    
    private function endGame()
    {
        $this->info('Game crashed');
        //event()
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while(true)
        {
            $this->timer();
            $this->game();
            $this->endGame();
        }
    }
}
