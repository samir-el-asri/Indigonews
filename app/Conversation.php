<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function messages()
    {
        return $this->hasMany('App\Message')->orderBy("created_at", "asc");
    }
}
