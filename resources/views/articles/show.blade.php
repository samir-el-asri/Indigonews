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
                                <button class="btn btn-primary" data-toggle="modal" data-target="#articleDeleteModalCenter">delete article</button>
                                <form method="post" action="/articles/{{$article->id}}">
                                    <input hidden name="_method" value="DELETE">
                                    <input hidden name="_token" value="{{ csrf_token() }}">

                                    <div class="modal fade" id="articleDeleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="articleDeleteModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="articleDeleteModalCenterTitle">Confirm delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Are you sure you want to delete this article?</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        @endif
                    @endif
                    @include('articles.article_comments_section')
                </div>
            </div>
        </div>
    </div>
@endsection