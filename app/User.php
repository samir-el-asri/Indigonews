<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }
}
