<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Category is assigned to many Articles
    public function articles()
    {
        return $this->hasMany('App\Article')->orderBy("created_at", "desc");
    }
}
