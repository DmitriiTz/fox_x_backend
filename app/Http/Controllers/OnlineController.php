<?php

namespace App\Http\Controllers;

use App\GameType;
use App\HistoryGame;
use App\Message;
use App\Participant;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlineController extends Controller
{
    public function online()
    {
        return ['count_users'=>User::all()->where('is_online', '=', true)->count(),'users'=>User::all()->where('is_online', '=', true)];
    }

}