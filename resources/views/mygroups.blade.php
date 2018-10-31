@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h1>My pages</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-9">
                    <a class="btn btn-default pull-right" href="#">Refresh Groups</a>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        {{--<th>#</th>--}}
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    {{--<th scope="row">1</th>--}}
                    @foreach ($admin_groups as $group)
                        <td class="col-md-10"><a href="{{$group->link}}">{{$group->name}}</a></td>
                        <td class="col-md-2"><a href="{{route('group.disable', ['group' => $group])}}" class="btn btn-danger">Disable</a></td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection