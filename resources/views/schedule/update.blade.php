@extends('layouts.app')

@section('content')
    <div style="margin: auto; width: 40%">
        <div>
            <h1>Edit post</h1>
            <form method="post" action="{{route('post.edit', ['post' => $post])}}" enctype="multipart/form-data">

                <input type="hidden" name="_method" value="PUT"/>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <input type="hidden" name="group_id" value="{{ $post->group_id }}">

                <div>
                    <label for="group-select">Choose group</label>
                    <select name="group_id" id="group-select">
                        @foreach($admin_groups as $group)
                            <option value="{{$group->id}}"
                                    @if($group->id == $post->group_id)
                                    selected
                                    @endif
                            >
                                {{$group->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="text">Text:</label>
                    <input id='text' name="text" value="{{old('text') ?? $post->text}}"/>
                    @if($errors->has('text'))
                        <div>{{ $errors->first('text') }}</div>
                    @endif
                </div>
                <div>
                    <label for="post-date">Date:</label>
                    <input id="post-date" type="date" name="date" value="{{old('date') ?? $post->date}}" min="{{date('now')}}">
                    @if($errors->has('date'))
                        <div>{{ $errors->first('date') }}</div>
                    @endif
                </div>
                <div>
                    <label for="post-time">Time:</label>
                    <input id="post-time" type="time" name="time" value="{{old('time') ?? $post->time}}">
                    @if($errors->has('time'))
                        <div>{{ $errors->first('time') }}</div>
                    @endif
                </div>
                <div>
                    @foreach($images as $image)
                        {{--<a href="{{route('attachment.destroy', ['attachment' => $image])}}">X </a>--}}
                        <span class="image-destroy-btn">X </span>
                        <span>{{$image->name}}</span>
                        <br>
                    @endforeach
                    <label for="images">Add images</label>
                        <!-- Button trigger image modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#imageModal">
                            image gallery
                        </button>
                    <input type="file" accept=".jpeg,.png,.jpg,.gif" name="images[]" multiple>
                </div>
                <div>
                    @foreach($videos as $video)
                        <span class="video-destroy-btn">X </span>
                        <span>{{$video->name}}</span>
                        <br>
                    @endforeach
                    <label for="videos">Add videos</label>
                        <!-- Button trigger video modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#videoModal">
                            video gallery
                        </button>
                        <input type="file" accept="video/*" name="videos[]" multiple>
                </div>
                <button>Edit</button>
            </form>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yours image gallery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row justify-content-around">
                            @foreach($gallery['images'] as $item)
                                <div class="col-md-4 control-label">
                                    <img class="attachment-checkbox img-responsive center-block" value="{{$item->attachments->first()->id}}" width="120" height="120" src="https://cs10.pikabu.ru/post_img/big/2018/07/18/4/1531889898156631317.jpg" alt="">
                                    {{--<span class="attachment-checkbox" value="{{$item->attachments->first()->id}}">--}}
                                        {{--{{$item->name}}--}}
                                    {{--</span>--}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yours video gallery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            @foreach($gallery['videos'] as $item)
                                <div class="col-md-6">
                                    {{$item->name}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection