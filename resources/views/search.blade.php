@extends('layouts.app')

@section('content')
    <div align="center">
        <h3>My Category</h3>
        @foreach($user_category as $category)
            <p>{{$category->name}}<p/>
        @endforeach

        <h3>Custom Category</h3>
        @foreach($custom_category as $category)
            <p>{{$category->name}}<p/>
        @endforeach
    </div>
@endsection