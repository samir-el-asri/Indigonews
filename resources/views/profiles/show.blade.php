@extends('layouts.app')

@section('content')
<div class="team-clean">
    <div class="container">
        <div class="row people">
            <div class="col-md-6 col-lg-8 item mx-auto text-center">
                <div class="rounded-circle profile-image mb-4" style="background-image: url('/storage/profile_images/{{$profile->profile_image}}');"></div>
                <h3 class="fullname">{{$profile->fullname}}</h3>
                <p class="username">{{$profile->user->username}}</p>
                <p class="bio">{{$profile->bio}}</p>
            </div>
            @if (!Auth::guest())
                @if (Auth::user()->id != $profile->user_id)
                    <div class="col-md-4 col-lg-6 mx-auto text-center">
                        <form method="POST" action="/conversations">
                            <input hidden name="_token" value="{{ csrf_token() }}">
                            <input hidden name="profile_id" value="{{$profile->id}}">
                            <button class="cr-btn w-50" type="submit">send message</button>
                            </div>
                        </form>
                    </div>
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
            @if (Auth::user()->id == $profile->user_id)
                <div class="col-md-6 col-lg-6 item mx-auto text-center">
                    <hr>
                    <div class="btn-group" role="group">
                        <a href="/profiles/{{$profile->id}}/edit">
                            <button class="btn btn-primary" type="button">edit profile</button>
                        </a>
                        <form class="delete-account" method="post" action="/profiles/{{$profile->id}}">
                            <input hidden name="_method" value="DELETE">
                            <input hidden name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-primary" type="submit">delete account permanently</button>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

{{-- Script for confirmation dialog box before account delete --}}
<script>
    $(".delete-account").on("submit", function(){
        return confirm("Are you sure?", "Delete account permanently");
    });
</script>
@endsection