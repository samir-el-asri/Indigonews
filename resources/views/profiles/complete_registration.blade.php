@extends('layouts.app')

@section('content')
    <h2 class="text-center pt-2 pb-4">Complete Your Profile Info</h2>

    <div class="container">
        <ul class="progressBar">
            <li class="list-element">full name</li>
            <li class="list-element">gender</li>
            <li class="list-element">birth date</li>
            <li class="list-element">brief bio</li>
            <li class="list-element">profile image</li>
        </ul>
    </div>

    <form class="profile-editor" method="post" action="/profiles/{{$profile->id}}" enctype="multipart/form-data">
        <input hidden name="_token" value="{{ csrf_token() }}">
        <input hidden name="_method" value="PUT">
        <div class="from-group">
            <label for="fullname">Fullname</label>
            <input type="text" class="form-control form-element" name="fullname" placeholder="{{$profile->fullname}}">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <input type="text" class="form-control form-element" list="genders" name="gender" placeholder="choose gender">
            <datalist id="genders">
                <option selected>male</option>
                <option selected>female</option>
            </datalist>
        </div>
        <div class="form-group">
            <label for="date">Birth date</label>
            <input type="date" class="form-control form-element" name="birthday" placeholder="{{$profile->birthday}}">
        </div>
        <div class="from-group">
            <label for="bio">Bio</label>
            <textarea id="profile-ckeditor" class="form-control form-element" name="bio" rows="5" placeholder="{{$profile->bio}}"></textarea>
        </div>
        <div class="form-group mb-4">
            <label for="profile_image">Profile Image</label>
            <input type="file" class="form-control form-element" name="profile_image">
        </div>
        <div class="form-group mt-4">
            <button hidden type="submit" class="cr-btn">complete registration</button>
        </div>
        <div class="form-group mt-2">
            <a class="cr-btn" href="/profiles/{{$profile->id}}">skip</a>
        </div>
    </form>
@endsection