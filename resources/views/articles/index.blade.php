@extends('layouts.app')

@section('content')
    @if (count($articles) > 0)
        @foreach ($articles as $article)
            <div class="list-group-item mb-3">
                <img class="w-25" src="/storage/features/{{$article->feature}}">
                <a href="/articles/{{$article->id}}"><h2>{{$article->title}}</h2></a>
                <span>written by <b>{{$article->user->username}}</b> on <b><i>{{$article->created_at}}</i></b></span>
                <br>
                <span>category: <b><a href="/categories/{{$article->category_id}}">{{$article->category->name}}</a></b></span>
            </div>
        @endforeach
    @else
        <h3>No articles found!</h3>
    @endif

    @if (!Auth::guest())
        <hr>
        <a class="text-white" href="/articles/create"><button class="btn btn-success">Write new article</button></a>
    @endif
@endsection