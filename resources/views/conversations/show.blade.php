@extends('layouts.app')

@section('content')
    <div class="conversation col-md-10 mx-auto">
        @foreach ($conversation->messages as $message)
            @if ($message->user_id == Auth::user()->id)
                <div class="row message-row col-md-8 d-flex mt-2 offset-4">
            @else
                <div class="row message-row col-md-8 d-flex mt-2">
            @endif
                    <div class="col-md-2 w-100">
                        <div class="rounded-circle mx-auto" style="background-image: url(/storage/profile_images/{{$message->user->profile->profile_image}});"></div>
                        <p class="w-100 text-center"><a href="/profiles/{{$message->user->profile->id}}">{{$message->user->username}}</a></p>
                    </div>
                    <div class="col-md-10 w-100 align-self-center">
                        <p class="text-message">{{$message->message}}</p>
                        <span class="w-100 d-inline-block text-right">{{$message->created_at}}</span>
                        <span style="color: royalblue !important;" class="w-100 d-inline-block text-right">
                            @if ($message->read)
                                read
                            @else
                                unread
                            @endif
                        </span>
                    </div>
                </div>
        @endforeach
        <hr>
        <div class="col-md-12 mt-2 mx-auto">
            <form method="POST" action="/messages">
                <input hidden name="_token" value="{{ csrf_token() }}">
                <input hidden name="conversation_id" value="{{$conversation->id}}">
                <div class="form-row">
                    <div class="col-md-10">
                        <textarea name="message" cols="2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-2">
                        <button class="form-control w-100" type="submit">send message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection