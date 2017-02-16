@extends('master')

@section('content')
    <div class="white-wrapper">
        <div class="index s0 section">
            <div id="r1" class="row clearfix">
                <div id="c1" class="column" style="background-image: url(/images/indexResized.jpg);">

                </div>
                <div id="c2" class="column">
                    <div id="r2" class="row">
                        <div id="r4" class="row">
                            @foreach($mixedGallery as $picture)
                                <div class="column" style="background-image: url(/images/small/{{ $picture }}.jpg);"></div>
                            @endforeach
                        </div>
                    </div>
                    <div id="r3" class="row">
                        <div class="content">
                            <div>
                                <h1>Эти двое взялись за себя всеръёз</h1>
                                <p>Значительное улучшение физической формы за год: фитнес-<a href="{{ route('calendar:index', [], false) }}">календарь</a> и <a href="{{ route('diary:index', [], false) }}">дневник</a></p>
                            </div>
                        </div>
                        <div class="overlay"></div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($settings->text_about_project) || !empty($settings->text_about_us))
            <div class="s3 section">
                <div class="wrapper">
                    <h3>О проекте и авторах</h3>
                    <div class="grid">
                        <div class="x2 row clearfix">
                            <div class="column">
                                {!! $settings->text_about_project !!}
                            </div>
                            <div class="column">
                                {!! $settings->text_about_us !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($diary) && $diary->count())
            <div class="s2 section">
                <div class="wrapper">
                    <h3>Недавнее</h3>
                    <!--
                    <div class="grid">
                        <div class="x2 row clearfix">
                            <div class="column">

                            </div>
                            <div class="column clearfix">
                                <a href="{{ route('diary:index', [], false) }}" class="modal empty pair button">Фитнес-дневник</a>
                            </div>
                        </div>
                    </div>
                    -->

                    @include('diary.grid')
                </div>
            </div>
        @endif
    </div>

@endsection