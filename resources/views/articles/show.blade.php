@extends('layouts.app')

@section('content')

    <div class="article-clean">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="intro pb-4">
                        <h1 class="text-center">{{$article->title}}</h1>
                        <p class="text-center">
                            <span class="by">by&nbsp;</span>
                            <a href="/profiles/{{$article->user->profile->id}}">{{$article->user->profile->fullname}}</a>
                            <span class="date">{{$article->created_at}} </span>
                            @if ($article->likes->count() > 0)
                                {{-- Likes pop-up Bootstrap Modal --}}
                                &nbsp;&nbsp;<button style="font-size: 13px; margin-bottom: 4px; text-transform: none;" type="button" class="date btn btn-link mx-auto" data-toggle="modal" data-target=".bd-example-modal-sm">{{$article->likes->count()}} likes</button>
                                <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" style="text-align: left" id="exampleModalLongTitle">Likes</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <table class="table modal-table">
                                                <tbody>
                                                    @foreach ($likers as $liker)
                                                        <tr>
                                                            <td class="w-25">
                                                                <img style="width: 30px; height: 30px;" class="rounded-circle mx-auto" src="{{$liker->profile->profileImage()}}">
                                                            </td>
                                                            <td class="w-100">
                                                                <a class="blank-a mx-auto" href="/profiles/{{$liker->profile->id}}">&#64;{{$liker->username}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                {{-- Modal ends here --}}
                            @else
                                <span class="date" style="color: black; text-transform: none; font-weight: bold;">{{$article->likes->count()}} likes</span>
                            @endif
                        </p>
                        <img class="feature" src="{{$article->feature()}}" />
                    </div>
                    <div class="text">
                        <p>{!!$article->content!!}</p>
                    </div>
                    <p class="text-center">Categorty: <a href="/categories/{{$article->category_id}}">{{$article->category->name}}</a></p>
                    
                    @if (!Auth::guest())
                        {{-- <like-button article-id="{{$article->id}}" likes="{{$likes}}"></like-button> --}}
                        <form method="POST" action="/like/{{$article->id}}">
                            @csrf
                            <button class="cr-btn w-25 mb-4" type="submit">
                                @if ($likes)
                                    unlike article
                                @else
                                    like article
                                @endif
                            </button>
                        </form>
                        @canany(['update', 'delete'], $article)
                            <div class="btn-group" role="group">
                                @can('update', $article)
                                    <a href="/articles/{{$article->id}}/edit">
                                        <button class="btn btn-primary" type="button">edit article</button>
                                    </a>
                                @endcan
                                @can('delete', $article)
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#articleDeleteModalCenter">delete article</button>
                                    <form method="post" action="/articles/{{$article->id}}">
                                        @method('DELETE')
                                        @csrf

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
                                @endcan
                            </div>
                        @endcanany
                    @endif
                    @include('articles.article_comments_section')
                </div>
            </div>
        </div>
    </div>
@endsection