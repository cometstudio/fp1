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
                                <span><a href="{{ route('diary:index', [], false) }}">{{ $misc->name }}</a></span>
                            @endif
                            @if(!empty($calendar->collect_article) && !empty($calendar->title))
                                <span>&mdash;</span>
                                <span>День {{ $seasonDaysLeft }}</span>
                            @endif
                        </nav>
                    </div>
                    <div class="datepicker-wrapper column">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="s1 section">
        <div class="wrapper">
            @if(!empty($calendar->collect_article) && !empty($calendar->title))
                <h1>{{ $calendar->title }}</h1>
            @else
                <h1>День {{ $seasonDaysLeft }}</h1>
            @endif

            @if(!empty($calendar->collect_article) && !empty($calendar->text))
                {!! $calendar->text !!}
            @endif
        </div>
    </div>

    @if(!empty($calendar->collect_gallery) && !empty($calendar->gallery))
        <div class="s2 section">
            <div class="wrapper">
                <div class="media">
                    <div class="wrapper">
                        <?php $titles = $calendar->getGalleryTitles(); ?>
                        @foreach($calendar->getGallery() as $index=>$picture)
                            <div class="image">
                                <div class="img">
                                    <img src="/images/medium/{{ $picture }}.jpg" />
                                </div>
                                @if(!empty($titles[$index]))
                                    <div class="caption">{{ $titles[$index] }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="s3 section">
        <div class="wrapper clearfix">
            <div class="desktop-visible l">{{ \Date::getDateFromTime($calendar->start_at) }}</div>
            <div class="r"><i class="fa fa-eye"></i> {{ $calendar->views }} {{ \Dictionary::get('views', $calendar->views) }} <i class="fa fa-comment-o"></i>
                @if($calendar->comments_total)
                    <a href="#comments">{{ $calendar->comments_total }} {{ \Dictionary::get('comments', $calendar->comments_total) }}</a>
                @else
                    <a href="#comments">Ваш комментарий</a>
                @endif
            </div>
        </div>
    </div>

    @if(!empty($settings->text_about_project) || !empty($settings->text_about_us))
        <div class="s4 section">
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

    <div class="s7 section">
        <div class="wrapper">
            @include('comments.container')
        </div>
    </div>

</div>

@endsection