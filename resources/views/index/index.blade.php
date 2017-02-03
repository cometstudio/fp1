@extends('master')

@section('content')

    <div class="s0 section">
        <div id="r1" class="row clearfix">
            <div id="c1" class="column" style="background-image: url(https://c1.staticflickr.com/3/2906/14173864652_b5842aca01_b.jpg);">

            </div>
            <div id="c2" class="column">
                <div id="r2" class="row" style="background-image: url(https://c1.staticflickr.com/8/7299/14159786345_6ffd72f2da_z.jpg);">

                </div>
                <div id="r3" class="row">
                    <div class="content">
                        <div>
                            <h1>Header 1</h1>
                            <p>Some text goes here</p>
                            <p>&nbsp;</p>
                            <a href="" class="white empty button">Follow us</a>
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
                            <h3>Случайные фотогалереи</h3>
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