@extends('layouts.app')

@section('content')
    <div class="article-clean">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="intro">
                        <h1 class="text-center">{{$article->title}}</h1>
                        <p class="text-center">
                        <span class="by">by&nbsp;</span>
                        <a href="/profiles/{{$article->user->profile->id}}">{{$article->user->profile->fullname}}</a>
                        <span class="date">{{$article->created_at}} </span>
                        </p>
                        <img src="/storage/features/{{$article->feature}}" />
                    </div>
                    <div class="text">
                        <p>{!!$article->content!!}</p>
                    </div>
                    <p class="text-center">Categorty: <a href="/categories/{{$article->category_id}}">{{$article->category->name}}</a></p>
                    
                    @if (!Auth::guest())
                        @if (Auth::user()->id == $article->user_id)
                            <hr>
                            <div class="btn-group" role="group">
                                <a href="/articles/{{$article->id}}/edit">
                                    <button class="btn btn-primary" type="button">edit article</button>
                                </a>
                                <form method="post" action="/articles/{{$article->id}}">
                                    <input hidden name="_method" value="DELETE">
                                    <input hidden name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-primary" type="submit">delete article</button>
                                </form>
                            </div>
                        @endif
                    @endif
                    
                    {{-- Comments Section --}}
                    <hr>
                    <div class="container comments-section">
                        @if (!Auth::guest())
                            <div class="row comment-row">
                                <div class="col-lg-2">
                                    <div class="rounded-circle comment-user-photo" style="background-image: url(/storage/profile_images/{{Auth::user()->profile->profile_image}});"></div>
                                </div>
                                <div class="col" style="height: 100px;" >
                                    <form method="POST" action="/comments">
                                        <input hidden name="_token" value="{{ csrf_token() }}">
                                        <input hidden name="article_id" value="{{$article->id}}">
                                        <input type="text" name="comment" class="w-100" placeholder="add a comment">
                                        <input class="btn btn-primary w-100 mt-2" type="submit" value="add comment">
                                    </form>
                                </div>
                            </div>
                        @endif
                        
                        @if (count($comments) > 0)
                            @foreach ($comments as $comment)
                                <div class="row comments-row">
                                    <div class="col-lg-2">
                                        <div class="rounded-circle comment-user-photo" style="background-image: url(/storage/profile_images/{{$comment->user->profile->profile_image}});"></div>
                                        <p class="text-center"><a class="username-and-date " href="/profiles/{{$comment->user->profile->id}}">{{$comment->user->username}}</a></p>
                                    </div>
                                    <div class="col">
                                        <p class="comment w-100 text-left">{!! $comment->comment !!}</p>
                                        <p class="username-and-date w-100 text-right">{{ floor((abs(strtotime(date("Y-m-d")) - strtotime($comment->created_at))/(60*60*24))) + 1 }} days ago</p>
                                        @if (!Auth::guest())
                                            @if (Auth::user()->id == $comment->user_id)
                                                <form action="/comments/{{$comment->id}}" method="post">
                                                    <input hidden name="_token" value="{{ csrf_token() }}">
                                                    <input hidden name="_method" value="delete">
                                                    <button class="text-danger text-right" type="submit">delete</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-12">
                                    <p>no comments found!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection