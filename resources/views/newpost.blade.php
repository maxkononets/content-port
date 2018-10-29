@extends('layouts.app')

@section('content')
@foreach($admin_groups as $group)
   <p>{{$group}}</p>
@endforeach
@endsection