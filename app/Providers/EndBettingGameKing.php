<?php

namespace App\Jobs;

use App\HistoryGame;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EndBettingGameKing implements ShouldQueue
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
    public $game;

    public $tries = 1;
    public function __construct($gameId, $endGameAt, $type)
    {
        $this->gameId = $gameId;
        $this->endGameAt = $endGameAt;
        $this->game = HistoryGame::find($gameId);
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if($this->game->end_game_at < now()->addSeconds(1) && !$this->game->is_view) {
            event(new \App\Events\EndBettingGameKing($this->gameId, $this->game->end_game_at, $this->type));
        }


    }
}
