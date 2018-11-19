@extends('layouts.app')

@section('content')
    <div style="margin: auto; width: 40%">
        <h1>New post</h1>
        <form  id="edit-post-form" method="post" action="{{route('post.store')}}" enctype="multipart/form-data">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">


            <div>
                <label for="group">Choose group</label>
                <select name="group_id" id="group-select">
                    @foreach($admin_groups as $group)
                        <option value="{{$group->id}}">
                            {{$group->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="text">Text:</label>
                <input id='text' name="text" value="{{old('text')}}"/>
                @if($errors->has('text'))
                    <div>{{ $errors->first('text') }}</div>
                @endif
            </div>
            <div>
                <label for="date">Date:</label>
                <input type="date" name="date" value="{{old('date')}}" min="{{date('now')}}">
                @if($errors->has('date'))
                    <div>{{ $errors->first('date') }}</div>
                @endif
            </div>
            <div>
                <label for="time">Time:</label>
                <input type="time" name="time" value="{{old('time')}}">
                @if($errors->has('time'))
                    <div>{{ $errors->first('time') }}</div>
                @endif
            </div>
            <div>
                <label for="images">Add images</label>
                <input type="file" accept=".jpeg,.png,.jpg,.gif" name="images[]" multiple>
            </div>
            <div>
                <label for="videos">Add videos</label>
                <input type="file" accept="video/*" name="videos[]" multiple>
            </div>
            <button>Shedule</button>
        </form>
    </div>
@endsection