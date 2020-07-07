@extends('layouts.app')

@section('content')
    
    <div class="mx-auto col-10">
        <h2>Search results for "{{$search_query}}"</h2>
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a role="tab" data-toggle="tab" class="nav-link active" href="#tab-1">Articles</a></li>
            <li class="nav-item"><a role="tab" data-toggle="tab" class="nav-link" href="#tab-2">People</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active mb-2" id="tab-1">
                @if (count($articles) > 0)
                    {{-- {!! $articles->links() !!} --}}
                    <div class="container articles_home">
                        @foreach ($articles as $article)
                            <div class="row">
                                <div class="col-md-6" style="padding: 20px;">
                                    <h2><a href="articles/{{$article->id}}">{{$article->title}}</a></h2>
                                    <p><em>Written by&nbsp;&nbsp;</em><a class="author" href="/profiles/{{$article->user->profile->id}}">{{$article->user->profile->fullname}}</a></p>
                                    <span style="color: black">{{count($article->comments)}} comments</span>
                                    <span class="pl-1" style="color: black; border-left: 1px solid lightgray;">{{count($article->likes)}} likes</span>
                                    <span class="pl-1" style="color: black; border-left: 1px solid lightgray;">Category: <a href="/categories/{{$article->category_id}}" style="color: dimgray;">{{$article->category->name}}</a></span>
                                    <hr>
                                    <p>{!! $article->content !!}</p>
                                </div>
                                <div class="col-md-6 feature" style="background-image: url('{{$article->feature()}}'); border-left: 1.5px solid lightgray;"></div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="pt-4 pl-2">No articles found matching your search query.</p>
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-2">
                @if (count($profiles_users) > 0)
                    <div class="container articles_home">
                        @foreach ($profiles_users as $pu)
                            <div class="row w-100 mx-auto">
                                @if (get_class($pu) == "App\User")
                                    <div class="col-2">
                                        <div class="rounded-circle pu-photo" style="background-image: url({{$pu->profile->profileImage()}});"></div>
                                    </div>
                                    <div class="col">
                                        <p class="pu-username">@<a href="profiles/{{$pu->profile->id}}">{{$pu->username}}</a></p>
                                    </div>
                                @else
                                    <div class="col-2">
                                        <div class="rounded-circle pu-photo" style="background-image: url({{$pu->profileImage()}});"></div>
                                    </div>
                                    <div class="col">
                                        <p class="pu-username"><a href="profiles/{{$pu->id}}">{{$pu->fullname}}</a></p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="pt-4 pl-2">No people found matching your search query.</p>
                @endif
            </div>
        </div>
        <br>
        <img class="offset-10" src="/storage/img/search-by-algolia.png" alt="Search by Algolia">
    </div>
        
@endsection