@extends('master')

@section('content')
    <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <div class="breadcrumbs">
                    <nav>
                        <a href="{{ route('index', [], false) }}">Стартовая</a>
                    </nav>
                </div>
            </div>
        </div>

        <div class="s0 section">
            <div class="wrapper">
                <h1>{{ $misc->name or 'Header' }}</h1>
                {!! $misc->text or '' !!}
            </div>
        </div>

        @if(!empty($diary) && $diary->count())
            <div class="s1 section">
                <div class="wrapper">
                    @include('diary.grid')

                    {{ $diary->appends(request()->all())->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection