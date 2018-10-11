@extends('layouts.app')

@section('content')
    <div id="mega" align="center">
        <div id="date">
            <h3>{{$group->name}} -({{$group->schedulePosts->count()}})</h3>
        </div>
        <div id="posts">
            @foreach($posts as $post)
                <p>{{$post->date_to_post}}</p>
                <p>{{$post->text}}</p>
                <a href="{{route('post.update', ['post' => $post])}}">Edit</a>
                <a style="color: red;" href="{{route('post.destroy', ['post' => $post])}}">delete</a>
            @endforeach
        </div>
    </div>
@endsection