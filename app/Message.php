<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function account() {
        return $this->belongsTo(User::class, 'account_id');
    }
}
