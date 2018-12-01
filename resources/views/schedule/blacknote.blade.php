<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><b>New post</b></div>
                <div class="panel-body">
                    <form id="edit-post-form" method="post" action="{{route('post.store')}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="group-select">Choose group:</label>
                                <select class="form-control" name="group_id" id="group-select">
                                    @foreach($admin_groups as $group)
                                        <option value="{{$group->id}}">
                                            {{$group->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="comment">Text:</label>
                            <textarea class="form-control" rows="5" cols="60" id="comment">
                                {{old('text')}}
                            </textarea>
                            @if($errors->has('text'))
                                <div>{{ $errors->first('text') }}</div>
                            @endif
                        </div>
                        <div id="datetimepicker">d</div>
                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker').datetimepicker({
                                    inline: true,
                                    sideBySide: true
                                });
                            });
                        </script>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
</div>