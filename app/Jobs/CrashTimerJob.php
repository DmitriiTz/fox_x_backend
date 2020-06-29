<?php

namespace App\Jobs;

use App\Events\EndGameTimerCrash;
use App\HistoryGame;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CrashTimerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $gameId;
    public $endGameAt;
    public $coef;
    public $endTimer;
    public $game;

    public function __construct($gameId, $i, $coef, $endTimer)
    {
        $this->endTimer = $endTimer;
        $this->gameId = $gameId;
        $this->game = HistoryGame::find($gameId);
        $this->endGameAt = $i;
        $this->coef = $coef;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->endGameAt === $this->endTimer){
            $this->game->status = 3;
            $this->game->save();
        }
        event(new \App\Events\CrashTimer($this->gameId, $this->endGameAt, $this->coef, $this->endTimer));
    }
}
