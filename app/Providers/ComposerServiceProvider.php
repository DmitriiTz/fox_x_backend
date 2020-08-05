<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Message;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('messages', $this->getMessages());
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function getMessages() {

        $messages = Message::with('account')->orderBy('created_at', 'desc')->take(40)->get();
//        $messages = null;
        return $messages;
    }
}
