<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EndGameCoinflip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $gameId;
    public $bankUser;
//    public $endGameAt;
//    public $winnerName;
//    public $color;
//    public $cash, $balance, $accountId;

    public function __construct($gameId, $bankUser)
    {
        $this->gameId = $gameId;
        $this->bankUser = $bankUser;

//        $this->endGameAt = $endGameAt;
//        $this->winnerName = $winnerName;
//        $this->color = $color;
//        $this->cash = $cash;
//        $this->balance = $balance;
//        $this->accountId = $accountId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new \App\Events\EndGameCoinflip($this->gameId, $this->bankUser));
    }
}
