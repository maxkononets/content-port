@extends('layouts.app')

@section('content')
    <input id="category-route" type="hidden" value="{{route('user.category.get.posts.json', ['category' => $category])}}">
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
            @foreach($groups as $group  )
                <p>
                    <a href="{{$group->link}}">{{$group->name}}</a>
                    <a href="{{route('group.destroy', ['group' => $group, 'category' => $category->id])}}" style="color: red">X</a>
                </p>
            @endforeach
        </div>
        @endif
        <div class="posts" id="posts-block">
            <label for="post-per-page">Posts per page</label>
            <select name="post-per-page" id="post-per-page-select">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <button id="show-more-posts" value="4">show more posts</button>
        <div id="content">
        </div>
    </div>

    {{--Modal video window--}}
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <iframe width="100%" height="400px" src="" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    {{--Modal post window--}}
    {{--<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-body">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    {{--<form  id="edit-post-form" method="post" action="{{route('post.store')}}" enctype="multipart/form-data">--}}
                        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                        {{--<div>--}}
                            {{--<label for="group">Choose group</label>--}}
                            {{--<select name="group_id" class="form-control" id="group-select">--}}
                                {{--@foreach(Auth::user()->adminGroups(true) as $group)--}}
                                    {{--<option value="{{$group->id}}">--}}
                                        {{--{{$group->name}}--}}
                                    {{--</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<label for="text">Text:</label>--}}
                            {{--<textarea  class="form-control" rows="3" id='text' name="text"></textarea>--}}
                            {{--@if($errors->has('text'))--}}
                                {{--<div>{{ $errors->first('text') }}</div>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<label for="date">Date:</label>--}}
                            {{--<input type="date" name="date" class="form-control" value="{{old('date')}}" min="{{date('now')}}">--}}
                            {{--@if($errors->has('date'))--}}
                                {{--<div>{{ $errors->first('date') }}</div>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<label for="time">Time:</label>--}}
                            {{--<input type="time" name="time" class="form-control" value="{{old('time')}}">--}}
                            {{--@if($errors->has('time'))--}}
                                {{--<div>{{ $errors->first('time') }}</div>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<label>Added images</label>--}}
                            {{--<div class="pre-scrollable form-control " >--}}
                                {{--d <br>--}}
                                {{--d <br>--}}
                                {{--d <br>--}}
                                {{--d <br>--}}
                                {{--d <br>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div id="image-modal-selector">--}}
                            {{--<label for="images">Add images</label>--}}
                            {{--<input type="file" accept=".jpeg,.png,.jpg,.gif" name="images[]" multiple>--}}
                        {{--</div>--}}
                        {{--<div id="video-modal-selector">--}}
                            {{--<label for="videos">Add videos</label>--}}
                            {{--<input type="file" accept="video/*" name="videos[]" multiple>--}}
                        {{--</div>--}}
                        {{--<button>Shedule</button>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}




    <div class="container-fluid" id="post-form-container" hidden>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Schedule post</b></div>
                    <div class="panel-body">
                        <form  id="edit-post-form" method="post" action="{{route('post.store')}}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type="hidden" name="store-attachment-route" id="store-url" value="{{ route('store.attachments') }}">
                            <div>
                                <label for="group">Choose group</label>
                                <select name="group_id" class="form-control" id="group-select">
                                    @foreach($admin_groups as $group)
                                        <option value="{{$group->id}}">
                                            {{$group->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label for="text">Text:</label>
                                <textarea class="form-control" id='text' name="text" rows="5">{{old('text')}}</textarea>
                                @if($errors->has('text'))
                                    <div>{{ $errors->first('text') }}</div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="checkbox form-group col-md-4">
                                    <label>
                                        <input type="checkbox" id="datetime-checkbox" > Schedule post
                                    </label>
                                </div>
                                <div id="datetime-block" class="col-md-8" hidden>
                                    <div class="col-md-6">
                                        <label for="date">Date:</label>
                                        <input type="date" name="date" class="form-control datetime-select" value="{{old('date')}}" min="{{date('now')}}">
                                        @if($errors->has('date'))
                                            <div>{{ $errors->first('date') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="time">Time:</label>
                                        <input type="time" name="time" class="form-control datetime-select" value="{{old('time')}}">
                                        @if($errors->has('time'))
                                            <div>{{ $errors->first('time') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row container-fluid form-attachments-block" id="form-attachments-block" hidden>
                                <label for="attachments-block">Attachments</label>
                                <div class="row" id="attachments-block">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 images-block-shows" hidden>
                                        <label for="images-block">Images</label>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 images-block"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 videos-block-shows" hidden>
                                        <label for="videos-block">Videos</label>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 videos-block"></div>
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
        <script src="{{ asset('js/category.js') }}"></script>
        <script src="{{ asset('js/post_editor.js') }}"></script>
    @endpush
@endsection
