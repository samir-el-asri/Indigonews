@extends('layouts.app')

@section('content')
<div class="team-clean">
    <div class="container">
        <div class="row people">
            <div class="col-md-6 col-lg-8 item mx-auto text-center">
                <div class="rounded-circle profile-image mb-4" style="background-image: url({{$profile->profileImage()}});"></div>
                <h3 class="fullname">{{$profile->fullname}}</h3>
                <p class="username">{{$profile->user->username}}</p>

                {{-- Followers: --}}
                @if ($profile->followers->count() > 0 && !Auth::guest())
                    {{-- Followers pop-up Bootstrap Modal --}}
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target=".followers-modal">{{$profile->followers->count()}} Followers</button>
                    <div class="modal fade followers-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="text-align: left" id="exampleModalLongTitle">Followers</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <table class="table">
                                    <tbody>
                                        @foreach ($followers as $follower)
                                            <tr>
                                                <td class="w-25">
                                                    <img style="width: 30px; height: 30px;" class="rounded-circle mx-auto" src="{{$follower->profile->profileImage()}}">
                                                </td>
                                                <td class="w-100">
                                                    <a style="font-size: 16px;" class="blank-a mx-auto" href="/profiles/{{$follower->profile->id}}">&#64;{{$follower->username}}</a>
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
                    <span class="zero-follow">{{$profile->followers->count()}} Followers</span>
                @endif

                {{-- Following: --}}
                @if ($profile->user->following->count() > 0 && !Auth::guest())
                    {{-- Following pop-up Bootstrap Modal --}}
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target=".following-modal">Following {{$profile->user->following->count()}}</button>
                    <div class="modal fade following-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="text-align: left" id="exampleModalLongTitle">Following</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <table class="table">
                                    <tbody>
                                        @foreach ($followings as $following)
                                            <tr>
                                                <td class="w-25">
                                                    <img style="width: 30px; height: 30px;" class="rounded-circle mx-auto" src="{{$following->profile->profileImage()}}">
                                                </td>
                                                <td class="w-100">
                                                    <a style="font-size: 16px;" class="blank-a mx-auto" href="/profiles/{{$following->profile->id}}">&#64;{{$following->username}}</a>
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
                    <span class="zero-follow">Following {{$profile->user->following->count()}}</span>
                @endif
                
                <p class="bio">{{$profile->bio}}</p>
                @if (!Auth::guest())
                    @if (Auth::user()->profile->id != $profile->id)
                        @if (!$blocking and !$blocked)
                            {{-- <follow-button profile-id="{{$profile->id}}" followed="{{$followed}}"></follow-button> --}}
                            <form method="POST" action="/follow/{{$profile->id}}">
                                @csrf
                                <button class="cr-btn w-25 mb-4" type="submit">
                                    @if ($followed)
                                        unfollow
                                    @else
                                        follow
                                    @endif
                                </button>
                            </form>
                        @endif
                    @endif
                @endif
                @if (!Auth::guest())
                    @if (Auth::user()->profile->id != $profile->id)
                        @if (!$blocked)
                            <form method="POST" action="/block/{{$profile->id}}">
                                @csrf
                                @if ($blocking)
                                    <button style="border-color: rgb(180, 0, 0) !important;" class="cr-btn w-25 mb-4" type="submit">
                                        unblock
                                    </button>
                                @else
                                    <button class="cr-btn w-25 mb-4" type="submit">
                                        block
                                    </button>
                                @endif
                            </form>
                        @else
                            <h6 class="text-center">
                                <strong>You are blocked from following &commat;{{$profile->user->username}}</strong>
                            </h6>
                        @endif
                    @endif
                @endif
            </div>
            @if (!Auth::guest())
                @if (Auth::user()->id != $profile->user_id)
                    @if (!$blocking and !$blocked)
                        <div class="col-md-4 col-lg-6 mx-auto text-center">
                            <form method="POST" action="/conversations">
                                @csrf
                                <input hidden name="profile_id" value="{{$profile->id}}">
                                <button class="cr-btn w-50" type="submit">send message</button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif
            @endif
            <div class="col-md-6 col-lg-6 item mx-auto text-center">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><strong>Age:</strong></td>
                                <td>{{ floor((abs(strtotime(date("Y-m-d")) - strtotime($profile->birthday))/(60*60*24*30*12))) }} years old</td>
                            </tr>
                            <tr>
                                <td><strong>Gender:</strong></td>
                                <td>{{$profile->gender}}</td>
                            </tr>
                            <tr>
                                <td><strong>Date Joined:</strong></td>
                                <td>{{substr($profile->user->created_at, 0, 10)}}</td>
                            </tr>
                            @if (!Auth::guest())
                                @if (Auth::user()->id == $profile->user_id)
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{$profile->user->email}}</td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
                    
        @if (!Auth::guest())
            @canany(['update', 'delete'], $profile)
                <div class="col-md-6 col-lg-6 item mx-auto text-center">
                    <hr>
                    <div class="btn-group" role="group">
                        @can('update', $profile)
                            <a href="/profiles/{{$profile->id}}/edit">
                                <button class="btn btn-primary" type="button">edit profile</button>
                            </a>
                        @endcan
                        @can('delete', $profile)
                            <button class="btn btn-primary" data-toggle="modal" data-target="#profileDeleteModalCenter">delete account permanently</button>
                            <form class="delete-account" method="post" action="/profiles/{{$profile->id}}">
                                @method('DELETE')
                                @csrf
                                <div class="modal fade" id="profileDeleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="profileDeleteModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="profileDeleteModalCenterTitle">Confirm delete</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to delete this profile?</div>
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
                </div>
            @endcanany
        @endif
    </div>
</div>

@endsection