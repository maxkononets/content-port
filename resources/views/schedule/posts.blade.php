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
                    <p>{{$post->publication_time}}</p>
                    <a class="form-edit" href="/posts/update/{{$post->id}}/Europa/Kiev">Edit</a>
                    <a style="color: red;" href="{{route('post.destroy', ['post' => $post])}}">delete</a>
                </div>
                {{--<div>--}}
                    {{--<form id="edit-post" class="form-edit" method="post" action="{{route('post.update', ['post' => $post])}}">--}}
                        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    {{--</form>--}}
                {{--</div>--}}
                <br>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/post_editor.js') }}"></script>
    @endpush
@endsection