<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
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

    // Profiles likes many Articles
    public function liking()
    {
        return $this->belongsToMany('App\Article')->withTimestamps();
    }
}
