<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Profile extends Model
{
    use Searchable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', 'gender', 'birthday', 'bio', 'profile_image'
    ];

    public function profileImage(){
        $path = '/storage/profile_images/'.($this->profile_image);
        return $path;
    }

    // Profile belongs to one single User
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Profile engages in many Conversations
    public function conversations()
    {
        return $this->hasMany('App\Conversation');
    }

    // Profile is followed by many Users
    public function followers()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    // User is blocked by many Profiles
    public function blockers()
    {
        return $this->belongsToMany('App\User', 'profile_user_block_pivot')->withTimestamps();
    }

    // Profiles likes many Articles
    public function liking()
    {
        return $this->belongsToMany('App\Article')->withTimestamps();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->only('id', 'fullname');
        
        return $array;
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'profiles_index';
    }
}
