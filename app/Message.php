<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Conversation;

class Message extends Model
{
    public function photo(){
        $path = ($this->conversation->path).'/'.($this->photo);
        return $path;
    }

    // Message belongs to one single Conversation
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    // Message is sent by one single user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
