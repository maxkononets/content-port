@extends('layouts.app')

@section('content')
@foreach ($personal_pages as $personal)
    <p>{{$personal}}<p/>
@endforeach
<br/>
@foreach ($admin_groups as $admin)
<p>{{$admin}}<p/>
@endforeach
@endsection