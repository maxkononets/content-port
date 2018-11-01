@extends('layouts.app')

@section('content')
    <div id="mega"  style="margin: auto; width: 40%">
        <div id="date">
            <h3>{{$group->name}} ({{$group->schedulePosts->count()}})</h3>
        </div>
        <div id="posts">
            @foreach($posts as $post)
                <div>
                    <p>{{$post->text}}</p>
                    <p>{{$post->date}} {{$post->time}}</p>
                    <a href="{{route('post.update', ['post' => $post])}}">Edit</a>
                    <a style="color: red;" href="{{route('post.destroy', ['post' => $post])}}">delete</a>
                </div>
                <br>
            @endforeach
        </div>
    </div>
@endsection