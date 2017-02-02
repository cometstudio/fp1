@extends('master')

@section('content')

    <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <h1>{{ $misc->name or 'Header' }}</h1>

                <form action="{{ route('supplements:index', [], false) }}" method="get">
                    <input name="ob" value="{{ request()->get('ob') }}" type="hidden" />
                    <input name="o" value="{{ request()->get('o') }}" type="hidden" />
                    <div class="grid">
                        <div class="x2 row clearfix">
                            <div class="column">
                                <input name="q" value="{{ request()->get('q') }}" type="text" placeholder="Название продукта..." />
                            </div>
                            <div class="column">
                                <button class="button">Найти</button>
                                @if(request()->all())
                                    <a href="{{ route('supplements:index', [], false) }}" class="empty accent button">Сбросить фильтры</a>
                                @endif
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="s1 section">
            <div class="wrapper">
                @if(!empty($supplements) && $supplements->count())
                    <div class="suppliments-grid">
                        <div class="row clearfix">
                            <div class="column{{ !request()->has('ob') ? ' active' : '' }}">
                                <a href="{{ route('supplements:index', [], false) }}" title="Сортировать по алфавиту">Название</a>
                            </div>
                            <div class="column{{ request()->get('ob') == 'protein' ? ' active' : '' }}">
                                <a href="{{ route('supplements:index', ['ob'=>'protein', 'o'=>(request()->get('ob') == 'protein'  && !request()->has('o') ? 'desc' : ''), 'q'=>request()->get('q')], false) }}" title="Сортировать по {{ (request()->get('ob') == 'protein') && request()->has('o') ? 'увеличению' : 'уменьшению' }}" rel="nofollow">Белок</a>, г
                            </div>
                            <div class="column{{ request()->get('ob') == 'fat' ? ' active' : '' }}">
                                <a href="{{ route('supplements:index', ['ob'=>'fat', 'o'=>(request()->get('ob') == 'fat'  && !request()->has('o') ? 'desc' : ''), 'q'=>request()->get('q')], false) }}" title="Сортировать по {{ (request()->get('ob') == 'fat') && request()->has('o') ? 'увеличению' : 'уменьшению' }}" rel="nofollow">Жиры</a>, г
                            </div>
                            <div class="column{{ request()->get('ob') == 'carbohydrates' ? ' active' : '' }}">
                                <a href="{{ route('supplements:index', ['ob'=>'carbohydrates', 'o'=>(request()->get('ob') == 'carbohydrates'  && !request()->has('o') ? 'desc' : ''), 'q'=>request()->get('q')], false) }}" title="Сортировать по {{ (request()->get('ob') == 'carbohydrates') && request()->has('o') ? 'увеличению' : 'уменьшению' }}" rel="nofollow">Углеводы</a>, г
                            </div>
                            <div class="column{{ request()->get('ob') == 'energy' ? ' active' : '' }}">
                                <a href="{{ route('supplements:index', ['ob'=>'energy', 'o'=>(request()->get('ob') == 'energy'  && !request()->has('o') ? 'desc' : ''), 'q'=>request()->get('q')], false) }}" title="Сортировать по {{ (request()->get('ob') == 'energy') && request()->has('o') ? 'увеличению' : 'уменьшению' }}" rel="nofollow">Энергия</a>, ККал
                            </div>
                        </div>
                        @foreach($supplements as $supplement)
                            <div class="row clearfix">
                                <div class="column">
                                    {{ $supplement->name }}
                                </div>
                                <div class="column">
                                    {{ $supplement->protein }}
                                </div>
                                <div class="column">
                                    {{ $supplement->fat }}
                                </div>
                                <div class="column">
                                    {{ $supplement->carbohydrates }}
                                </div>
                                <div class="column">
                                    {{ $supplement->energy }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection