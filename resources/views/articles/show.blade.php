@extends('layouts.app')

@section('content')
    @if (!Auth::guest())
        @if (Auth::user()->id == $article->user_id)
            <div class="d-flex">
                <a class="text-dark" href="/articles/{{$article->id}}/edit"><button class="btn btn-warning">edit</button></a>
                <form class="ml-2" method="post" action="/articles/{{$article->id}}">
                    <input hidden name="_method" value="DELETE">
                    <input hidden name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-danger">delete</button>
                </form>
            </div>
            <hr>    
        @endif
    @endif

    <div class="list-group-item">
        <img class="w-25" src="/storage/features/{{$article->feature}}">
        <h2>{{$article->title}}</h2>
        <span>written by <b>{{$article->user->username}}</b> on <b><i>{{$article->created_at}}</i></b></span>
        <br>
        <span>category: <b><a href="/categories/{{$article->category_id}}">{{$article->category->name}}</a></b></span>
        <hr>
        <p>{!!$article->content!!}</p>
    </div>

    <hr>
    <a class="text-white" href="/articles"><button class="btn btn-secondary">Back to articles</button></a>
@endsection