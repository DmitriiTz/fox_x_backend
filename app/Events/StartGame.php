<?php

namespace App\Events;

use App\HistoryGame;
use App\Events\EndGame;
use App\Jobs\EndGameTimer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StartGame implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $gameId;
    public $endGame;
    public $gameTypeName;
    public $hashGame;
    public $endGameAt;
    public $game;
    public $end_game_at;
    public $JackpotController;

    public function __construct($gameId, $endGame, $gameTypeName,$endGameAt)
    {
        $this->gameId = $gameId;
        $this->game = HistoryGame::find($this->gameId);
        $this->end_game_at = $endGame;
        $this->endGame = now()->diffInSeconds($endGame);
        $this->gameTypeName = $gameTypeName;
        $this->hashGame = $this->game->hash;
        $this->endGameAt = $endGameAt;
     //   $this->JackpotController = new JackpotController();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        
        info('timer - '. ($this->endGame - 1) );
            if($this->endGame - 1  <= $this->endGameAt)
        {

        if($this->endGameAt == 0)
        {  
             event(new EndGame($this->game->id, $this->end_game_at));


        }
        else
        {
        $this->endGameAt = $this->endGameAt -1;
        return new PresenceChannel('online-users');
        }
    }
    }
}
