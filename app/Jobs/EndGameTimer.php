<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EndGameTimer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timer;
    public $tries = 1;

    public $gameId;
    public $hash;
    public $winnerTicket;
    public $linkHash;
    public $time;
    public $percent;
    public $image;
    public $name;
    public $bank;
    public $winnerId;
    public $viewHistoryWinner;
    public $accountId;
    public $balanceUser;
    public $winners;

    public function __construct($mainTimer,$temp,$gameId,$hash,$winnerTicket,$linkHash,$timer,$percent,$image,$name,$bank,$winnerId,$viewHistoryWinner,$accountId,$balanceUser,$winners)
    {
     $this->gameId  = $gameId;
$this->hash = $hash;
$this->winnerTicket = $winnerTicket;
$this->linkHash = $linkHash;
$this->time = $timer;
$this->percent = $percent;
$this->image = $image;
$this->name = $name;
$this->bank = $bank;
$this->winnerId = $winnerId;
$this->viewHistoryWinner = $viewHistoryWinner;
$this->accountId = $accountId;
$this->balanceUser = $balanceUser;
$this->winners = $winners;
        $this->timer = $mainTimer;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
 
        event(new \App\Events\EndGameTimer($this->timer,$this->gameId,$this->hash,$this->winnerTicket,$this->linkHash,$this->time,$this->percent,$this->image,$this->name,$this->bank,$this->winnerId,$this->viewHistoryWinner,$this->accountId,$this->balanceUser,$this->winners));

    }
}
