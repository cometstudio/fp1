@extends('master')

@section('content')

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
                            <p>Регулярные тренировки и строгая диета в течение года сделают из них людей </p>
                        </div>
                    </div>
                    <div class="overlay"></div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($videos) && $videos->count())
        <div class="s1 section">
            <div class="wrapper">
                <!--
                <div class="grid">
                    <div class="x2 row clearfix">
                        <div class="column">
                            <h3>Недавние видеоотчёты</h3>
                        </div>
                        <div class="column clearfix">
                            <a href="{{ route('videos:index', [], false) }}" class="empty pair button">Смотреть все видео</a>
                        </div>
                    </div>
                </div>
                -->

                @include('videos.grid')
            </div>
        </div>
    @endif

    @if(!empty($gallery) && $gallery->count())
        <div class="s2 section">
            <div class="wrapper">
                <div class="grid">
                    <div class="x2 row clearfix">
                        <div class="column">
                            <h3>Фотодневник</h3>
                        </div>
                        <div class="column clearfix">
                            <a href="{{ route('gallery:index', [], false) }}" class="modal empty pair button">Смотреть все фото</a>
                        </div>
                    </div>
                </div>

                @include('gallery.grid')
            </div>
        </div>
    @endif

@endsection