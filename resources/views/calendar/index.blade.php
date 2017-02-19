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
                            @if(!empty($calendar->collect_article) && !empty($calendar->text))
                                <span>&mdash;</span>
                                <span>День {{ $seasonDaysLeft }}</span>
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
            @if(!empty($calendar->collect_article) && !empty($calendar->text))
                @if(!empty($calendar->title))
                    <h1>{{ $calendar->title }}</h1>
                @endif
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
                                    <img src="/images/medium/{{ $picture }}.jpg" title="{{ !empty($titles[$index]) ? $titles[$index] : '' }}" />
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

    @if((request()->session()->get('about_us_seen') < 2) && (!empty($settings->text_about_project) || !empty($settings->text_about_us)))
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

    <div class="s5 section">
        <div class="wrapper">
            <h3>Рацион питания на этот день</h3>
            <?php $j = 0; ?>
            @foreach($meals as $meal)
                @if($j) <p>&nbsp;</p> @endif
                <p class="b">{{ $meal->name }}</p>
                @if(!empty($recipes) && $recipes->count())
                    <?php
                    $k=0;
                    foreach($recipes->filter(function($recipe) use ($meal){ return $recipe->meal_id == $meal->id; }) as $recipe){
                        if($k){
                            echo ', '.$recipe->name;
                        }else{
                            echo $recipe->name;
                        }

                        $k++;
                    }
                    ?>
                @endif
                <?php $j++; ?>
            @endforeach
        </div>
    </div>

    <div class="s6 section">
        <div class="wrapper">
            <div class="macros-table grid">
                <div class="row clearfix">
                    <div class="column">&nbsp;</div>
                    <div class="column">
                        <span class="desktop-visible">Саша</span>
                        <span class="mobile-visible">С</span>
                    </div>
                    <div class="column">
                        <span class="desktop-visible">Настя</span>
                        <span class="mobile-visible">Н</span>
                    </div>
                    <div class="column">
                        <span class="desktop-visible">% от нормы</span>
                        <span class="mobile-visible">%</span>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="column">Белки, г</div>
                    <div class="column">{{ $totalMacros['protein'] }}</div>
                    <div class="column">{{ $totalMacros['protein_f'] }}</div>
                    <div class="{{ $totalMacros['protein_daily']['active'] ? 'active ' : '' }}daily column">{{ $totalMacros['protein_daily']['total'] }}%</div>
                </div>
                <div class="row clearfix">
                    <div class="column">Жиры, г</div>
                    <div class="column">{{ $totalMacros['fat'] }}</div>
                    <div class="column">{{ $totalMacros['fat_f'] }}</div>
                    <div class="{{ $totalMacros['fat_daily']['active'] ? 'active ' : '' }}daily column">{{ $totalMacros['fat_daily']['total'] }}%</div>
                </div>
                <div class="row clearfix">
                    <div class="column">Углеводы, г</div>
                    <div class="column">{{ $totalMacros['carbohydrates'] }}</div>
                    <div class="column">{{ $totalMacros['carbohydrates_f'] }}</div>
                    <div class="{{ $totalMacros['carbohydrates_daily']['active'] ? 'active ' : '' }}daily column">{{ $totalMacros['carbohydrates_daily']['total'] }}%</div>
                </div>
                <div class="row clearfix">
                    <div class="column">Энергия, ККал</div>
                    <div class="column">{{ $totalMacros['energy'] }}</div>
                    <div class="column">{{ $totalMacros['energy_f'] }}</div>
                    <div class="{{ $totalMacros['energy_daily']['active'] ? 'active ' : '' }}daily column">{{ $totalMacros['energy_daily']['total'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($exercises) && $exercises->count())
        <div class="s8 section">
            <div class="wrapper">
                <h3>Упражнения</h3>
                <div class="exercises-grid grid">
                    @foreach($exercises as $exercise)
                        <div class="x2 row clearfix">
                            <div class="b column">
                                {{ $exercise->name }}
                            </div>
                            <div class="column">
                                @if(!empty($exercise->notice))
                                    {{ $exercise->notice }} повторений
                                @endif
                            </div>
                        </div>
                    @endforeach
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