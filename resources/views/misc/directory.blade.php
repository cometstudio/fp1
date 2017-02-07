@extends('master')

@section('content')

    <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <h1>{{ $misc->name }}</h1>
                @if($kids = $misc->kids()->get())
                    <ul>
                    @foreach($kids as $kid)
                        <li><a href="{{ route('supplements:directory', ['subalias'=>$kid->alias], false) }}">{{ $kid->name }}</a></li>
                    @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection