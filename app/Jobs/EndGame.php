<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EndGame implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $gameId;
    public $endGameAt;
    public $tries = 1;

    public function __construct($gameId, $endGameAt)
    {
        $this->gameId = $gameId;
        $this->endGameAt = $endGameAt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
 
        event(new \App\Events\EndGame($this->gameId, $this->endGameAt));

    }
}
