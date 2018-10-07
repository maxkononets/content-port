@extends('layouts.app')

@section('content')
    <div align="center">
        <form action="#" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            Search on phrase: <input type="text" name="phrase" value="{{old('phrase')}}">
            <button>search</button>
        </form>
    </div>
    <div align="center">
        <h3>My Category</h3>
        <form action="{{route('store.category')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            Add Category: <input type="text" name="name" value="{{old('name')}}">
            <button>add</button>
        @if($errors->has('name'))
                <div>{{ $errors->first('name') }}</div>
        @endif
        </form>
        @foreach($user_category as $category)
            <p>
                <a href="#">{{$category->name}}</a>
                <a href="{{route('category.destroy', ['category' => $category])}}" style="color: red">X</a>
            </p>
        @endforeach

        <h3>Custom Category</h3>
        @foreach($custom_category as $category)
            <p>{{$category->name}}</p>
        @endforeach
    </div>
@endsection
