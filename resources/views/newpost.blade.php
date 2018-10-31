@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h1>New post</h1>
            <form method="post" action="{{route('post.store')}}" enctype="multipart/form-data">
                <label for="group-select">Choose group</label>
                <select name="group_id" class="form-control" id="group-select">
                    @foreach($admin_groups as $group)
                        <option value="{{$group->id}}">
                            {{$group->name}}
                        </option>
                    @endforeach
                </select>
                <div class="form-group">
                    <label class="control-label" for="text">Text:</label>
                    <input type="text" name="text" id="text" class="form-control" value="{{old('text')}}">
                    @if($errors->has('text'))
                        <span class="help-block">{{ $errors->first('date') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type='text' name="date" class="form-control" value="{{old('date')}}" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        @if($errors->has('date'))
                            <span class="help-block">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="image">Add Images</label>
                    <input type="file" id="image" accept=".jpeg,.png,.jpg,.gif" name="images[]" multiple>
                </div>
                <div class="form-group">
                    <label for="video">Add Videos</label>
                    <input type="file" id="video" accept="video/*" name="videos[]" multiple>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $('#datetimepicker').datetimepicker({
            minDate: moment()
        });
    </script>
@endpush
