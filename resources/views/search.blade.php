@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h1>Content Search</h1>
            <div class="row">
                <div class="col-md-12">
                    <form method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="input-group">
                            <input type="text" name="phrase" value="{{old('phrase')}}" class="form-control" placeholder="Search on phrase...">
                            <span class="input-group-btn">
                      <button type="button" class="btn btn-flat">
                          <i class="glyphicon glyphicon-search"></i>
                      </button>
                    </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <h3>My Category</h3>
                    <form action="{{route('store.category')}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="input-group">
                                <input type="text" name="name" id="text" class="form-control" value="{{old('name')}}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-flat">
                                      Add Category
                                    </button>
                                </span>
                            @if($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    @foreach($user_category as $category)
                        <a href="{{route('category.show', ['category' => $category])}}">{{$category->name}}</a>
                        <a href="{{route('category.destroy', ['category' => $category])}}" class="btn btn-flat btn-danger">
                            Delete
                        </a>
                    @endforeach
                </div>
                <div class="col-md-12">
                    <h3>Custom Category</h3>
                    @foreach($custom_category as $category)
                        <p><a href="{{route('custom.category.show', ['category' => $category])}}">{{$category->name}}</a></p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
