<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public $with = ['account'];

    public function account() {
        return $this->belongsTo(User::class, 'account_id');
    }
}
