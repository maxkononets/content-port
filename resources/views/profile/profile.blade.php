@extends('layouts.app')
@section('sidebar')
 <p>hello</p>
@endsection
@section('content')
<div>
    <img src="{{$user['avatar']}}"><br>
Your name:    {{$user['name']}}<br>
Your email: {{$user['email']}}
</div>
@endsection