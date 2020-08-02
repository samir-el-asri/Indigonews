@extends('layouts.app_nofooter')

@section('content')
    <div class="col-10 pb-4 mx-auto d-flex justify-content-end">
        <button class="btn btn-danger border-dark" data-toggle="modal" data-target="#convoDeleteModalCenter">Delete conversation</button>     
        <form method="POST" action="/exclude/{{$conversation->id}}">
            @csrf
            <div class="modal fade" id="convoDeleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="convoDeleteModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="convoDeleteModalCenterTitle">Confirm delete</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">Are you sure you want to delete this conversation?</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="conversation col-10 mx-auto" id="scrollingConversation">
        @if (!empty($messages))
            @foreach ($messages as $message)
                @if ($message->user_id == Auth::user()->id)
                    <div class="row message-row col-8 d-flex mt-2 offset-4">
                @else
                    <div class="row message-row col-8 d-flex mt-2">
                @endif
                        <div class="col-2 w-100">
                            <div class="rounded-circle mx-auto" style="background-image: url({{$message->user->profile->profileImage()}});"></div>
                            <p class="w-100 text-center"><a href="/profiles/{{$message->user->profile->id}}">{{$message->user->username}}</a></p>
                        </div>
                        <div class="col-10 w-100 align-self-center">
                            <p class="text-message">{{$message->message}}</p>
                            @if (!is_null($message->photo))
                                <img class="w-100 p-2" src="{{$message->photo()}}">
                            @endif
                            <span class="w-100 d-inline-block text-right">{{$message->created_at}}</span>
                            <div class="row w-100">
                                <div class="col-2 offset-11 w-100">
                                    @if ($message->read)
                                        <img src="/storage/img/seen.png" class="w-50">
                                    @else
                                        <img src="/storage/img/unseen.png" class="w-50">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
        @endif
        <hr>
        <div class="col-12 mt-2 mx-auto">
            @if ($blocked)
                <h5 class="text-center">
                    <strong>You can no longer send messages to this person!</strong>
                </h5>
            @else
                <form method="POST" action="/messages" enctype="multipart/form-data">
                    @csrf
                    <input hidden name="conversation_id" value="{{$conversation->id}}">
                    <div class="form-row">
                        <div class="col-9 mb-2">
                            <textarea name="message" cols="2" class="form-control"></textarea>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col mb-1 w-100">
                                    <input type="file" name="photo" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col w-100">
                                    <button id="sendMessageBtn" class="form-control" type="submit">send message</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
            @endif
        </div>
    </div>
@endsection