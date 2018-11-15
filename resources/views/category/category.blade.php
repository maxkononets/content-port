@extends('layouts.app')

@section('content')
    <div id="content" align="center">
        <h2>{{$category->name}}</h2>
        <div id="search">
            <form action="#" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <label for="phrase">Search on phrase in category:</label>
                <input id="phrase" type="text" name="phrase">
                <button>search</button>
            </form>
        </div>
        @if($category instanceof \App\UserCategory)
        <div id="sources">
            <h3>Sources</h3>
            <form action="{{route('store.group')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="category" value="{{ $category->id }}">
                <label for="link">Add group on link:</label>
                <input id="link" type="text" name="link">
                <button>add</button>
            </form>
            @if($errors->has('link'))
                <div style="color: orange">{{ $errors->first('link') }}</div>
            @endif
            @foreach($groups as $group)
                <p>
                    <a href="{{$group->link}}">{{$group->name}}</a>
                    <a href="{{route('group.destroy', ['group' => $group, 'category' => $category->id])}}" style="color: red">X</a>
                </p>
            @endforeach
        </div>
        @endif
        <div id="content">
        </div>
    </div>
@endsection