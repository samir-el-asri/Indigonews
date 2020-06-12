<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use App\Article;


class LikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Article $article)
    {
        auth()->user()->profile->liking()->toggle($article);
        return Redirect('/articles/'.$article->id);
    }
}
