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
    //public $view;
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
        //$this->view = '';
        info('timer - ' . ($this->endGameTime - now()->timestamp));
        if (($this->endGameAt - 1) % 2 == 0) {
            $this->game = HistoryGame::with(['winner','participants' => function ($query) {
                $query->with('account')->orderBy('cash', 'desc');
            }])
                ->where('id', $this->gameId)
                ->first();
            if (count($this->game->participants) > 0) {
                $participant = $this->game->participants[0];

                //dump(['1 step' => $participant->account_id]);

                $this->image = $participant->account->image;
            }
        } else {
            $participants = Participant::where('history_game_id', $this->gameId)->orderBy('cash', 'desc')->get();
            if (isset($participants[0])) {
                $participant = $participants[0];
                //dump(['2 step' => $participant->account_id]);
            }
            $this->image = User::find($participant->account_id)->image;
            $this->game = HistoryGame::whereId($this->gameId)->with('winner')->first();
            $this->game->winner_account_id = $participant->account_id;
            $this->game->save();
        }
        if (strtotime($this->game->end_game_at) - now()->timestamp - 1 <= $this->endGameAt) {
            if ($this->endGameAt == 0) {
                //dump(['game_id' => $this->game->game_id]);
                event(new EndGameKing($this->game->id, $this->game->end_game_at, $this->type));
            }
            $this->endGameAt = $this->endGameAt - 1;

            return new Channel('king');
        }
    }
}
