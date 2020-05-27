<?php

namespace App\Events;

use App\HistoryGame;
use App\Events\EndGameKing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EndGameTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $timer;
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

    public function __construct($mainTimer,$gameId,$hash,$winnerTicket,$linkHash,$timer,$percent,$image,$name,$bank,$winnerId,$viewHistoryWinner,$accountId,$balanceUser,$winners)
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
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
     //   info('timer - '. ($this->endGameTime - now()->timestamp));
      
        return new PresenceChannel('online-users');
    
    }
}
