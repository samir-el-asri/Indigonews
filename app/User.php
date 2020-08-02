<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use Searchable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // Assigning a default profile at user creation

    protected static function boot(){
        parent::boot();
        static::created(function($user){
            $user->profile()->create([
                'fullname' => 'John Doe',
                'gender' => 'male',
                'birthday' => '2000-01-01',
                'bio' => 'empty',
                'profile_image' => 'noimage.jpg'
            ]);
            $user->save();
        });
    }

    // User posts many Articles
    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    // User owns one single Profile
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    // User posts many Comments
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    // User sends many Messages
    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    // User starts many Conversations
    public function conversations()
    {
        return $this->hasMany('App\Conversation');
    }

    // User deletes (excludes/hides) many Conversations
    public function excluding()
    {
        return $this->belongsToMany('App\Conversation')->withTimestamps();
    }

    // User follows many Profiles
    public function following()
    {
        return $this->belongsToMany('App\Profile')->withTimestamps();
    }

    // User blocks many Profiles
    public function blocking()
    {
        return $this->belongsToMany('App\Profile', 'profile_user_block_pivot')->withTimestamps();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->only('id', 'username');
        
        return $array;
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'users_index';
    }
}
