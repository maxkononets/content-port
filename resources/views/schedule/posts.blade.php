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
                    <button form="edit-post">Edit</button>
                    <a style="color: red;" href="{{route('post.destroy', ['post' => $post])}}">delete</a>
                </div>
                <div>
                    <form id="edit-post" method="post" action="{{route('post.update', ['post' => $post])}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
                <br>
            @endforeach
        </div>
    </div>

@endsection