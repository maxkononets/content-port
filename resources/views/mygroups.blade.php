@extends('layouts.app')

@section('content')
    <div style="margin: auto; width: 40%">
    <h1>My pages</h1>
        <h4><a href="#">refresh groups</a></h4>
@foreach ($admin_groups as $group)
            <a href="{{$group->link}}">{{$group->name}}</a>
            <a style="color: red" href="{{route('group.disable', ['group' => $group])}}">disable</a>
            <br>
@endforeach
    </div>
@endsection