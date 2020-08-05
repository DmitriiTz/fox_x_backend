<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $with = ['account'];

    public function account() {
        return $this->belongsTo(User::class, 'account_id');
    }
}
