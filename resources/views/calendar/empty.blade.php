@extends('master')

@section('content')

<div class="content-wrapper">
    <div class="s0 section">
        <div class="wrapper">
            <div class="grid">
                <div class="x2 row clearfix">
                    <div class="breadcrumbs column">
                        <nav>
                            <a href="{{ route('index', [], false) }}">Стартовая</a>
                            @if(!empty($misc))
                                <span>&mdash;</span>
                                <span>{{ $misc->name }}</span>
                            @endif
                        </nav>
                    </div>
                    <div class="datepicker-wrapper column">
                        @include('common.dateSelector')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="s1 section">
        <div class="wrapper">
            <h1>День {{ $seasonDaysLeft }}</h1>
        </div>
    </div>
</div>

@endsection