@extends('layouts.app')

@section('content')
    <div style="margin: auto; width: 50%">
    <div>
        <h1>Edit post</h1>
        <form method="post" action="{{route('post.edit', ['post' => $post])}}">

            <input type="hidden" name="_method" value="PUT" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input type="hidden" name="group_id" value="{{ $post->group_id }}">

            <div>
                <label for="text">Text:</label>
                <input id='text' name="text" value="{{old('text') ?? $post->text}}"/>
                @if($errors->has('text'))
                    <div>{{ $errors->first('text') }}</div>
                @endif
            </div>
            <div>
                <label for="">Date:</label>
                <input type="date" name="date" value="{{old('date') ?? $post->date}}" min="{{date('now')}}">
                @if($errors->has('date'))
                    <div>{{ $errors->first('date') }}</div>
                @endif
            </div>
            <div>
                <label for="">Time:</label>
                <input type="time" name="time" value="{{old('time') ?? $post->time}}">
                @if($errors->has('time'))
                    <div>{{ $errors->first('time') }}</div>
                @endif
            </div>
            <button>Edit</button>
        </form>
    </div>
    </div>
@endsection
