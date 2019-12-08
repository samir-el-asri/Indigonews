@extends('layouts.app')

@section('content')
    <div class="send-new-message col-md-10 mx-auto">
        <h4 class="pt-2 pb-2">Send new message</h4>
        <form method="POST" action="/messages">
            <input hidden name="_token" value="{{ csrf_token() }}">
            <div class="form-row">
                <div class="col-md-12">
                    <textarea name="message" cols="4" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-8">
                    <div class="dropdown w-100" style="display: block;width: 100%;">
                        <button class="dropdown-toggle w-100" data-toggle="dropdown" aria-expanded="true" type="button">select user</button>
                        <ul role="menu" class="dropdown-menu w-100" id="choose-message-recipient">
                            @foreach ($users as $user)
                                <li role="presentation" class="dropdown-item d-flex" href="#">
                                    <div class="rounded-circle" style="background-image: url('/storage/profile_images/{{$user->profile->profile_image}}');"></div>
                                    <p class="pl-4 align-self-end">{{$user->profile->fullname}}</p>
                                    <span hidden>{{$user->profile->id}}</span>
                                </li>
                            @endforeach
                        </ul>
                        <input hidden type="text" name="profile_id" id="choose-message-recipient-input">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="w-100" type="submit">send message</button>
                </div>
            </div>
        </form>
    </div>
    @if (count($conversations) > 0)
        <div class="inbox col-md-10 mx-auto">
            <h4 class="pt-4">Conversations</h4>
            @foreach ($conversations as $conversation)
                <hr>
                <a href="/conversations/{{$conversation->id}}">
                    <div class="row d-flex">
                        <div class="col-md-2">
                            @if (Auth::user()->id == $conversation->user_id)
                                <div class="rounded-circle mx-auto" style="background-image: url('/storage/profile_images/{{$conversation->profile->profile_image}}');"></div>
                                <p class="pt-2 d-block text-center">{{$conversation->profile->user->username}}</p>
                            @else
                                <div class="rounded-circle mx-auto" style="background-image: url('/storage/profile_images/{{$conversation->user->profile->profile_image}}');"></div>
                                <p class="pt-2 d-block text-center">{{$conversation->user->username}}</p>
                            @endif
                        </div>
                        <div class="col-md-10 w-100 align-self-center">
                            <p class="text-message">{{$conversation->messages->last()->message}}</p>
                            <span class="d-block w-100 text-right">{{$conversation->messages->last()->created_at}}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <h4 class="col-md-10 mx-auto pt-4">No conversations found!</h4>
    @endif
@endsection