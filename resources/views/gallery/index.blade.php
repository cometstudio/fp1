@extends('master')

@section('content')

    <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <h1>{{ $misc->name or 'Header' }}</h1>
                {!! $misc->text or '' !!}
            </div>
        </div>

        @if(!empty($gallery) && $gallery->count())
            <div class="s1 section">
                <div class="wrapper">
                    @include('gallery.grid')
                </div>
            </div>
        @endif
    </div>
@endsection