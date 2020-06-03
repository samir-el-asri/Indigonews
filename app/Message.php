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

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
