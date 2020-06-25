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
        dump(['timer' => $this->endGameAt, 'endGame' => $this->endTimer]);
        if($this->endGameAt === $this->endTimer){
            dump(['EndCrashTimerJob start']);
            for ($i = 0; $i <= 10; $i++) {
                $job = (new EndCrashTimerJob($i))->delay($i);
                $this->dispatch($job);
            }

//            for ($i = 0,$j = 10; $i <= 10,$j >= 0; $i++,$j--) {
//                $job = (new EndCrashTimerJob($this->game->id, $j, $this->coef))->delay($i);
//                $this->dispatch($job);
//            }
        }
        event(new \App\Events\CrashTimer($this->gameId, $this->endGameAt, $this->coef));
    }
}
