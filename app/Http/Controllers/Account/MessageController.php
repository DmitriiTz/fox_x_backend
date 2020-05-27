<?php

namespace App\Http\Controllers\Account;

use App\Events\SendMessage;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function sendMessage(Request $request) 
    {

        $user = auth()->user();
        $messageText = $request->message;
        if(!$user->is_blocked)
        {
        $chat_black_list = array(
            '&amp;',
            '&quot;',
            '&#039;',
            '&lt;',
            '&gt;',
            'csgo',
            'com',
            'ru',
            'net',
            'club',
            'ua',
            'org',
            'eu',
            'говно',
            'развод',
            'подкрут',
            'накрут',
            'кидал',
            'обман',
            'лагает',
            'наеб'
        );

        foreach ($chat_black_list as $word) {
            if(strpos($messageText, $word) !== false) {
                return '';
            }
        }

        $message = new Message;
        $message->account_id = $user->id;
        $message->text = $messageText;
        $message->save();

        $messageView = view('blocks.message', compact('message'))->render();

        event(new SendMessage($messageView));
         }
    }
}
