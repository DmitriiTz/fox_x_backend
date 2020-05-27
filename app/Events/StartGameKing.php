<?php

namespace App\Events;

use App\Participant;
use App\User;
use App\HistoryGame;
use App\Events\EndGameKing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StartGameKing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $gameId;
    public $endGameAt;
    public $type;
    public $endGameTime;
    public $view;
    public $game;
    public $image;

    public function __construct($gameId, $end_game_at, $type, $end_game_time)
    {
       
        $this->gameId = $gameId;
        $this->endGameAt = $end_game_at;
        $this->type = $type;
        $this->endGameTime = $end_game_time;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $this->view = '';
        info('timer - '. ($this->endGameTime - now()->timestamp));
         if(($this->endGameAt-1)%2 == 0)
            {
        $this->game = HistoryGame::with(['participants' => function($query) {
            $query->with('account')->orderBy('cash', 'desc');
        }])
            ->where('id', $this->gameId)
            ->first();
         if(count($this->game->participants) > 0)
         {
            $participant = $this->game->participants[0];
        
        $this->image = $participant->account->image;
        foreach($this->game->participants as $key => $participant)
        {
                                        if($key == 0)
                                        {   
                                            continue;
                                        }
                                       $this->view .= view('blocks.king-participants', compact('participant'))->render(); 
                                                                              
        }
        }
        }
        else
        {
           $participants = Participant::where('history_game_id', $this->gameId)->orderBy('cash', 'desc')->get();
        if(isset($participants[0])) {
            $participant = $participants[0];
        }
        $this->image = User::find($participant->account_id)->image;
            $this->game = HistoryGame::find($this->gameId);
        }
        if(strtotime($this->game->end_game_at) - now()->timestamp - 1 <= $this->endGameAt)
        {

        if($this->endGameAt == 0)
        {
            event(new EndGameKing($this->game->id, $this->game->end_game_at, $this->type));
        }
        $this->endGameAt = $this->endGameAt -1;
        return new PresenceChannel('online-users');
        }
    }
}
