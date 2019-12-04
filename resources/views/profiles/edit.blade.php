@extends('layouts.app')

@section('content')
    <form class="profile-editor" method="post" action="/profiles/{{$profile->id}}" enctype="multipart/form-data">
        <input hidden name="_token" value="{{ csrf_token() }}">
        <input hidden name="_method" value="PUT">
        <div class="from-group">
            <label for="fullname">Fullname</label>
            <input type="text" class="form-control" name="fullname" value="{{$profile->fullname}}">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" name="gender">
                @switch($profile->gender)
                    @case('male')
                        <option selected>male</option>
                        <option>female</option>
                        @break
                    @case('female')
                        <option>male</option>
                        <option selected>female</option>
                        @break
                @endswitch
            </select>
        </div>
        <div class="form-group">
            <label for="date">Birth date</label>
            <input type="date" class="form-control" name="birthday" value="{{$profile->birthday}}">
        </div>
        <div class="from-group">
            <label for="bio">Bio</label>
            <textarea id="profile-ckeditor" class="form-control" name="bio" rows="5">{{$profile->bio}}</textarea>
        </div>
        <div class="custom-file">
            <label for="profile_image">Profile Image</label>
            <input type="file" class="form-control" name="profile_image">
        </div>
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
@endsection