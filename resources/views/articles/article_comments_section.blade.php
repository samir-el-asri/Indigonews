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
                    {{-- <p class="username-and-date w-100 text-right">{{ floor((abs(strtotime(date("Y-m-d")) - strtotime($comment->created_at))/(60*60*24))) + 1 }} days ago</p> --}}
                    <p class="username-and-date w-100 text-right">{{ timeAgo($comment->created_at) }}</p>
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