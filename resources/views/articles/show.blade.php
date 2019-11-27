@extends('layouts.app')

@section('content')
    <div class="article-clean">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-8 offset-lg-1 offset-xl-2">
                    <div class="intro">
                        <h1 class="text-center">{{$article->title}}</h1>
                        <p class="text-center">
                        <span class="by">by&nbsp;</span>
                        <a href="#">{{$article->user->username}}</a>
                        <span class="date">{{$article->created_at}} </span>
                        </p>
                        <img src="/storage/features/{{$article->feature}}" />
                    </div>
                    <div class="text">
                        <p>{!!$article->content!!}</p>
                    </div>
                    <p class="text-center">Categorty: <a href="/categories/{{$article->category_id}}">{{$article->category->name}}</a></p>
                    <hr />
                    
                    @if (!Auth::guest())
                        @if (Auth::user()->id == $article->user_id)
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
                </div>
            </div>
        </div>
    </div>
@endsection