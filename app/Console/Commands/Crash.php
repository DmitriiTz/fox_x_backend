<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        for($i = 10; $i >=0; $i--)
        {
            sleep(1);
            $this->info($i);
          //  event(CrashTimer)
        }
    }

    private function game(int $time)
    {
        $this->info('starting game...');
        for($i = 0; $i <= $time; $i++)
        {
            time_nanosleep(0,(int)(1e9/30));
            $this->info($i);
          //  event(CrashGame)
        }
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
            $this->game(120);
            $this->endGame();
        }//
    }
}
