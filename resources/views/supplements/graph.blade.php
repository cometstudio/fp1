@extends('master')

@section('content')
       <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <h1>{{ $misc->name or 'Header' }}</h1>

                <form action="{{ route('supplements:graph', [], false) }}" method="get">
                    <input name="date" class="datepicker" value="{{ Date::getDateFromTime($startAt, 1) }}" onchange="this.form.submit();" type="text" style="width: auto;" />
                    <button class="button">Показать</button>
                </form>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="s1 section">
            <div class="wrapper">
                <h3>Энергия</h3>
                <div class="supplements-graph" style="margin: 0 0 36px;">
                    Maксимально в сутки ({{ $maxValue['energy'] }})
                    <div class="graph">
                        <div class="row clearfix">
                            @foreach($graphData as $day)
                                <div class="column">
                                    <div class="daily-value marker" style="height: {{ $day['energy_daily']['value'] * 100 / $maxValue['energy'] }}%;">
                                        <div class="{{ $day['energy_daily']['active'] ? 'active ' : '' }}total marker" style="height: {{ $day['energy_daily']['total'] }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row clearfix">
                        @foreach($graphData as $day)
                            <div class="column">
                                {{ \Date::getDateFromTime($day['calendar']->start_at, 2) }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <h3>Белки</h3>
                <div class="supplements-graph" style="margin: 0 0 36px;">
                    Maксимально в сутки ({{ $maxValue['protein'] }})
                    <div class="graph">
                        <div class="row clearfix">
                            @foreach($graphData as $day)
                                <div class="column">
                                    <div class="daily-value marker" style="height: {{ $day['protein_daily']['value'] * 100 / $maxValue['protein'] }}%;">
                                        <div class="{{ $day['protein_daily']['active'] ? 'active ' : '' }}total marker" style="height: {{ $day['protein_daily']['total'] }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row clearfix">
                        @foreach($graphData as $day)
                            <div class="column">
                                {{ \Date::getDateFromTime($day['calendar']->start_at, 2) }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <h3>Жиры</h3>
                <div class="supplements-graph" style="margin: 0 0 36px;">
                    Maксимально в сутки ({{ $maxValue['fat'] }})
                    <div class="graph">
                        <div class="row clearfix">
                            @foreach($graphData as $day)
                                <div class="column">
                                    <div class="daily-value marker" style="height: {{ $day['fat_daily']['value'] * 100 / $maxValue['fat'] }}%;">
                                        <div class="{{ $day['fat_daily']['active'] ? 'active ' : '' }}total marker" style="height: {{ $day['fat_daily']['total'] }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row clearfix">
                        @foreach($graphData as $day)
                            <div class="column">
                                {{ \Date::getDateFromTime($day['calendar']->start_at, 2) }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <h3>Углеводы</h3>
                <div class="supplements-graph" style="margin: 0 0 36px;">
                    Maксимально в сутки ({{ $maxValue['carbohydrates'] }})
                    <div class="graph">
                        <div class="row clearfix">
                            @foreach($graphData as $day)
                                <div class="column">
                                    <div class="daily-value marker" style="height: {{ $day['carbohydrates_daily']['value'] * 100 / $maxValue['carbohydrates'] }}%;">
                                        <div class="{{ $day['carbohydrates_daily']['active'] ? 'active ' : '' }}total marker" style="height: {{ $day['carbohydrates_daily']['total'] }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row clearfix">
                        @foreach($graphData as $day)
                            <div class="column">
                                {{ \Date::getDateFromTime($day['calendar']->start_at, 2) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection