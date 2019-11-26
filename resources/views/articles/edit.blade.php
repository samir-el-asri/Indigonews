@extends('layouts.app')

@section('content')
    <form method="post" action="/articles/{{$article->id}}" enctype="multipart/form-data">
        <input hidden name="_token" value="{{ csrf_token() }}">
        <input hidden name="_method" value="PUT">
        <div class="from-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" value="{{$article->title}}">
        </div>
        <div class="from-group">
            <label for="content">Content</label>
            <textarea id="article-ckeditor" class="form-control" name="content" rows="5">{{$article->content}}</textarea>
        </div>
        <div class="custom-file">
            <label for="feature">Feature</label>
            <input type="file" class="form-control" name="feature">
        </div>
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection