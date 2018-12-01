@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                {{--<div class="panel-heading">Login</div>--}}

                <div class="panel-body text-center">
                    <h2>Welcome to ContentPort</h2>
                    <h4>content menegment system</h4>
                    <p>pleas <a href="{{ route('facebook.login') }}"> LogIn with facebook</a> and enjoy us</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
