<?php

namespace App\Jobs;

use App\HistoryGame;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StartGameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $gameId;
    public $endGameAt;
    public $type;
  //  public $game;
    public $end_game_at;
    public $tries = 1;

    public function __construct($gameId, $end_game_at , $type,$endGameAt)
    {
        $this->gameId = $gameId;
        $this->endGameAt = $endGameAt;
        $this->type = $type;
        $this->end_game_at = $end_game_at;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
           event(new \App\Events\StartGame($this->gameId, $this->end_game_at, $this->type,$this->endGameAt));
    }
}
