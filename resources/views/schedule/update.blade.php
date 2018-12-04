@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Schedule post</b></div>
                    <div class="panel-body">
                        <form  id="edit-post-form" method="post" action="{{route('post.edit', ['schedulePost' => $post])}}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type="hidden" name="store-attachment-route" id="store-url" value="{{ route('store.attachments') }}">
                            @foreach($images as $image)
                                <input type="hidden" id="image{{$image->id}}" name="images[]" value="{{$image->id}}">
                                @endforeach
                            @foreach($videos as $video)
                                <input type="hidden" id="video{{$video->id}}" name="images[]" value="{{$video->id}}">
                                @endforeach
                            <div>
                                <label for="group">Choose group</label>
                                <select name="group_id" class="form-control" id="group-select">
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
                            <br>
                            <div>
                                <label for="text">Text:</label>
                                <textarea class="form-control" id='text' name="text" rows="5">{{old('text') ?? $post->text}}</textarea>
                                @if($errors->has('text'))
                                    <div>{{ $errors->first('text') }}</div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="checkbox form-group col-md-4">
                                    <label>
                                        <input type="checkbox" id="datetime-checkbox"
                                               @if(old('date') || old('time'))
                                               checked
                                               @else
                                               checked
                                                @endif
                                        > Schedule post
                                    </label>
                                </div>
                                <div id="datetime-block" class="col-md-8 " hidden>
                                    <div class="col-md-6 d-flex justify-content-around">
                                        <label for="date">Date:</label>
                                        <input type="date" name="date" id="date-id" class="form-control datetime-select" value="{{old('date') ?? $post->date}}">
                                        @if($errors->has('date'))
                                            <div>{{ $errors->first('date') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="time">Time:</label>
                                        <input type="time" name="time" class="form-control datetime-select" value="{{old('time') ?? $post->time}}">
                                        @if($errors->has('time'))
                                            <div>{{ $errors->first('time') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row container-fluid form-attachments-block" id="form-attachments-block"
                                 @unless($images || $videos)
                                 hidden
                                    @endunless
                            >
                                <label for="attachments-block">Attachments</label>
                                <div class="row" id="attachments-block">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 images-block-shows"
                                         @if(!$images)
                                         hidden
                                            @endif
                                    >
                                        <label for="images-block">Images</label>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 images-block">
                                            @foreach($images as $image)
{{--                                                {{dd($images)}}--}}
                                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 image{{$image->id}}" id="image{{$image->id}}">
                                                    <img class="img-rounded attachment-item-post" height="80" width="80" src="{{$image->route}}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 videos-block-shows"
                                         @if(!$videos)
                                         hidden
                                            @endif
                                    >
                                        <label for="videos-block">Videos</label>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 videos-block">
                                            @foreach($videos as $video)
                                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 video{{$video->id}}" id="video{{$video->id}}">
                                                    <video class="attachment-item-post" height="80" width="80" src="{{$video->route}}"></video>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--attachment places--}}
                            <div class="row">
                                <div class="col-md-9 col-sm-9 col-xs-9 col-lg-9">
                                    <submit class="btn btn-default btn-md gallery" data-post="" data-toggle="modal" data-target="#galleryModal">Add attachments</submit>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">
                                    <button class="btn btn-primary btn-xm">Shedule</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{--attachment modal window--}}
    <div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <b>Attachments panel</b>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="last-page-image" value="{{$gallery['image_last_page']}}">
                    <input type="hidden" id="last-page-video" value="{{$gallery['video_last_page']}}">
                    <div class="row container-fluid form-attachments-block" hidden>
                        <label for="attachments-block">Attachments</label>
                        <div class="row" id="attachments-block">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 images-block-shows" hidden>
                                <label for="images-block">Images</label>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 images-block text-center"></div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 videos-block-shows" hidden>
                                <label for="videos-block">Videos</label>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 videos-block text-center"></div>
                            </div>
                        </div>
                    </div>
                    {{--<br>--}}
                    <div class="row form-group text-center">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="images" class="btn btn-primary btn-file">Add images</label>
                            <input type="file" class="attachment hidden" id="images" accept=".jpeg,.png,.jpg,.gif" name="images[]" multiple>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="videos" class="btn btn-primary btn-file">Add videos</label>
                            <input type="file" class="attachment hidden" id="videos" accept="video/*" name="videos[]">
                        </div>
                    </div>
                    <div class="row container-fluid" id="gallery">
                        <label for="nav-tabs">Gallery</label>
                        <ul class="nav nav-tabs" id="nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#images-tab">Images</a></li>
                            <li><a data-toggle="tab" href="#videos-tab">Videos</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="images-tab" class="tab-pane fade in active text-center">
                                <div class="row container-fluid" id="images-tab-block">
                                    @foreach($gallery['images'] as $item)
                                        <div class="col-md-3  col-sm-3 col-lg-3 col-xs-4 text-center" id="image{{$item->id}}">
                                            <img class="img-rounded gallery-item" type="image" id="{{$item->id}}" height="80" width="80" src="{{$item->route}}" alt="...">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center">
                                        <submit class="btn btn-default btn-md gallery-prev" id="gallery-prev-image" type="image" value="">Prev</submit>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center">
                                        <submit class="btn btn-default btn-md gallery-next" id="gallery-next-image" type="image" value="{{$gallery['image_next']}}">Next</submit>
                                    </div>
                                </div>
                                {{--                                    {{$gallery['images']->links()}}--}}
                            </div>
                            <div id="videos-tab" class="tab-pane fade text-center">
                                <div class="row container-fluid"  id="videos-tab-block">
                                    {{--{{dd($gallery)}}--}}
                                    @foreach($gallery['videos'] as $item)
                                        <div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 text-center" id="video{{$item->id}}">
                                            <video class="gallery-item" type="video" id="{{$item->id}}" height="80" width="80" src="{{$item->route}}" alt="..."></video>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row container-fluid">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center">
                                        <submit class="btn btn-default btn-md gallery-prev" id="gallery-prev-video" type="video" value="">Prev</submit>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center">
                                        <submit class="btn btn-default btn-md gallery-next" id="gallery-next-video" type="video" value="{{$gallery['video_next']}}">Next</submit>
                                    </div>
                                </div>
                                {{--{{$gallery['videos']->links()}}--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/post_editor.js') }}"></script>
{{--        <script src="{{ asset('js/category.js') }}"></script>--}}
    @endpush
@endsection