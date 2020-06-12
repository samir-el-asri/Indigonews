<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'content', 'feature'
    ];

    public function feature(){
        $path = '/storage/features/'.($this->feature);
        return $path;
    }

    // Article is posted by one single User
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Article belongs in one single Category
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    // Article contains many Comments
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy("created_at", "desc");
    }

    // Article is liked by many Profiles
    public function likes()
    {
        return $this->belongsToMany('App\Profile')->withTimestamps();
    }
}
