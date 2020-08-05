<?php

namespace App\Jobs;

use App\Events\EndGameTimerCrash;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EndCrashTimerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $timer;

    public function __construct($timer)
    {
        $this->timer = $timer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new EndGameTimerCrash($this->timer));
    }
}
