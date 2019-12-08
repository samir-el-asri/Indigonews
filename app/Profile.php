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

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function conversations()
    {
        return $this->hasMany('App\Conversation');
    }
}
