@extends('layouts.app')

@section('content')
    <form class="profile-editor" method="post" action="/profiles/{{$profile->id}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
            <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#articleEditModalCenter">update profile</button>
            <div class="modal fade" id="articleEditModalCenter" tabindex="-1" role="dialog" aria-labelledby="articleEditModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="articleEditModalCenterTitle">Confirm modifications</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">Are you sure you want to update your profile?</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection