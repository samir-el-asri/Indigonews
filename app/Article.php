<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use Searchable;

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

    // Article has many Tags
    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    // Overriding the toSearchableArray method to index relationships between 'Article' and 'User'.
    public function toSearchableArray()
    {
        $array = $this->toArray();
        // Specifying which columns should be indexed(?)/searchable
        $array = $this->only('id', 'title', 'content');

        $array = $this->transform($array);

        $array['user_id'] = $this->user->id;
        // $array['user_id'] = $this->user['id'];
        $array['created_at'] = date('Y-m-d H:i:s', strtotime($this->created_at));

        return $array;
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'articles_index';
    }
}
