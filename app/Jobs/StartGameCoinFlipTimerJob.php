<?php

namespace App\Jobs;

use App\CrashGame;
use App\Events\EndGameTimerCrash;
use App\HistoryGame;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StartGameCoinFlipTimerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timer;
    public $game_id;

    public function __construct($game_id, $timer)
    {
        $this->game_id = $game_id;
        $this->timer = $timer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new \App\Events\StartGameCoinFlipTimer($this->game_id, $this->timer));
    }
}
