<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;

class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Profile $profile)
    {
        auth()->user()->following()->toggle($profile);
        return redirect('/profiles/'.$profile->id);
    }
}
