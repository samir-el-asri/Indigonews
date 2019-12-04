@extends('layouts.app')

@section('content')
    @if (count($articles) > 0)
        <div class="container articles_home">
            @for ($i = 0; $i < count($articles); $i++)
                @if ($i % 2 == 0)
                    <div class="row">
                        <div class="col-md-6" style="padding: 20px;">
                            <h2>{{$articles[$i]->title}}</h2>
                            <p><em>Written by </em><span class="author">{{$articles[$i]->user->profile->fullname}}</span> on <span class="date"><em>{{$articles[$i]->created_at}}</em></span></p>
                            <p>Category: <a href="/categories/{{$articles[$i]->category_id}}" style="color: dimgray;">comics</a></p><a href="/articles/{{$articles[$i]->id}}" style="color: rgb(38,17,82);font-weight: bold;">Read more...</a>
                        </div>
                        <div class="col-md-6 feature" style="background-image: url('/storage/features/{{$articles[$i]->feature}}'); border-left: 1.5px solid lightgray;"></div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6 feature" style="background-image: url('/storage/features/{{$articles[$i]->feature}}'); border-left: 1.5px solid lightgray;"></div>
                        <div class="col-md-6" style="padding: 20px;">
                            <h2>{{$articles[$i]->title}}</h2>
                            <p><em>Written by </em><span class="author">{{$articles[$i]->user->profile->fullname}}</span> on <span class="date"><em>{{$articles[$i]->created_at}}</em></span></p>
                            <p>Category: <a href="/categories/{{$articles[$i]->category_id}}" style="color: dimgray;">comics</a></p><a href="/articles/{{$articles[$i]->id}}" style="color: rgb(38,17,82);font-weight: bold;">Read more...</a>
                        </div>
                    </div>
                @endif
            @endfor
            {{-- @foreach ($articles as $article)
                <div class="row">
                    <div class="col-md-6" style="padding: 20px;">
                        <h2>{{$article->title}}</h2>
                        <p><em>Written by </em><span class="author">{{$article->user->username}}</span> on <span class="date"><em>{{$article->created_at}}</em></span></p>
                        <p>Category: <a href="/categories/{{$article->category_id}}" style="color: dimgray;">comics</a></p><a href="/articles/{{$article->id}}" style="color: rgb(38,17,82);font-weight: bold;">Read more...</a>
                    </div>
                    <div class="col-md-6 feature" style="background-image: url('/storage/features/{{$article->feature}}'); border-left: 1.5px solid lightgray;"></div>
                </div>
            @endforeach --}}
        </div>
    @else
        <h3>No articles found!</h3>
    @endif

    @if (!Auth::guest())
        <hr>
        <a class="text-white" href="/articles/create"><button class="btn btn-success">Write new article</button></a>
    @endif
@endsection