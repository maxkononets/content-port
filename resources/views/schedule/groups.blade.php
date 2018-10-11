@extends('layouts.app')

@section('content')
    <div id="groups" style="margin: auto; width: 50%">
        @foreach($groups as $group)
            <p>
                <a href="{{route('schedule.post', ['group' => $group])}}">{{$group->name}}</a>
                ({{$group->schedulePosts->count()}})
            </p>
        @endforeach
    </div>
@endsection