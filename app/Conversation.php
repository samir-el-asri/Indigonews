<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    // Conversation is started by one single User
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Conversation is engaged by one single Profile
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    // Conversation contains many Messages
    public function messages()
    {
        return $this->hasMany('App\Message')->orderBy("created_at", "asc");
    }

    // Conversation is deleted (excluded/hidden) by many Users
    public function excluders()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
