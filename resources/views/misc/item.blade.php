@extends('master')

@section('content')

    <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <h1>{{ $misc->name }}</h1>
                {!! $misc->text or '' !!}
            </div>
        </div>
    </div>
@endsection