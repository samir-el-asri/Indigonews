@extends('layouts.app')

@section('content')
    @if (!empty($alteredNotifications))
        <div class="notifications mx-auto">
            @foreach ($alteredNotifications as $alteredNotification)
                <div class="row d-flex justify-content-center align-items-center notification">
                    <div class="col-2 mx-auto">
                        <div class="rounded-circle user-pic" style="background: url({{$alteredNotification->userPic}}) center/contain;"></div>
                    </div>
                    <div class="col-10 mx-auto">
                        {!! $alteredNotification->text !!}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <h2>No Notifications!</h2>
    @endif
@endsection