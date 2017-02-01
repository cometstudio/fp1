@extends('panel.master')

@section('layout')
    <div class="clearfix">
        <div class="aside">
            @include('panel.user.menu')
        </div>
        <div class="content">
            @include('panel.controls')
            <div class="wrapper">
                @yield('content')
            </div>
        </div>
    </div>
@endsection