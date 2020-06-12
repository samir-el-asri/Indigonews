<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Comment is posted by one single User
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Comments belongs to one single Article
    public function article()
    {
        return $this->belongsTo('App\Article');
    }
}
