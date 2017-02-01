@extends('panel.edit.form')

@section('input')

    <div class="row">
        <dl>Дата начала</dl>
        <input name="_start_at" value="{{ $item->getStartDate() }}" type="text" class="x4 datepicker" autocomplete="off" />
    </div>
    @if(!empty($item->id))
        <div class="row" style="padding-bottom: 10px;">
            <dl>Питание</dl>
            @if(!empty($options['recipes']))
                <div style="margin-bottom: 10px;">
                    @if(!empty($options['meals']))
                        <select name="_meal_id">
                            <option value="">приём пищи...</option>
                            @foreach($options['meals'] as $meals))
                                <option value="{{ $meals->id }}">{{ str_limit($meals->name, 65) }}</option>
                            @endforeach
                        </select>
                    @endif
                    <select name="_recipe_id">
                        <option value="">блюдо...</option>
                        @foreach($options['recipes'] as $recipe))
                            <option value="{{ $recipe->id }}">{{ str_limit($recipe->name, 65) }}</option>
                        @endforeach
                    </select> <a onclick="return addBinding(this, $('.calendar-recipes'));" href="{{ route('admin::act', ['action'=>'bindrecipe', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$item->id], false) }}" class="empty button">Привязать</a>

                </div>
            @endif
            <div class="calendar-recipes">
                @include('panel.edit.calendarRecipes', ['item'=>$item, 'binded'=>$options['calendarRecipes']])
            </div>
        </div>
        <div class="row" style="padding-bottom: 10px;">
            <dl>Упражнения</dl>
            @if(!empty($options['exercises']))
                <div style="margin-bottom: 10px;">
                    <select name="_exercise_id">
                        <option value="">упражнение...</option>
                        @foreach($options['exercises'] as $exercise))
                            <option value="{{ $exercise->id }}">{{ str_limit($exercise->name, 65) }}</option>
                        @endforeach
                    </select>
                    <select name="_exercise_type">
                        <option value="">тип...</option>
                        @foreach(config('exercise_types') as $id=>$name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select> <a onclick="return addBinding(this, $('.calendar-exercises'));" href="{{ route('admin::act', ['action'=>'bindexercise', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$item->id], false) }}" class="empty button">Привязать</a>
                </div>
            @endif
            <div class="calendar-exercises">
                @include('panel.edit.calendarExercises', ['binded'=>$options['calendarExercises']])
            </div>
        </div>
    @endif
    <div style="margin: 1em 0;">
        <div class="row">
            Публиковать:
            <input name="collect_article" value="0" type="hidden" />
            <input name="collect_article" value="1" type="checkbox"{{ !empty($item->collect_article) ? ' checked' : '' }} /> <label>статью</label>
            <input name="collect_video" value="0" type="hidden" />
            <input name="collect_video" value="1" type="checkbox"{{ !empty($item->collect_video) ? ' checked' : '' }} /> <label> видео</label>
            <input name="collect_gallery" value="0" type="hidden" />
            <input name="collect_gallery" value="1" type="checkbox"{{ !empty($item->collect_gallery) ? ' checked' : '' }} /> <label>галерею</label>
        </div>
    </div>
    <div class="row">
        <dl>Заголовок, Title</dl>
        <input name="title" value="{{ $item->title }}" type="text" />
    </div>
    <div class="row">
        <dl>Текст</dl>
        <textarea name="text" class="ck">{{ $item->text }}</textarea>
    </div>
    <div class="row">
        <dl>Видео</dl>
        <input name="video" value="{{ $item->video }}" type="text" />
    </div>
@endsection