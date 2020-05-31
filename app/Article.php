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

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy("created_at", "desc");
    }
}
