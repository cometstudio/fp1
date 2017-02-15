@extends('panel.edit.form')

@section('input')

    <div class="row">
        <dl>Дата начала</dl>
        <input name="_start_at" value="{{ $item->getStartDate() }}" type="text" class="x4 datepicker" autocomplete="off" />
    </div>
    <div class="row">
        <input name="publish" value="0" type="hidden" />
        <input name="publish" value="1" type="checkbox" /> <label>опубликовать в календаре</label>
    </div>
    <div style="margin: 1em 0;">
        <div class="row">
            Публиковать:
            <input name="collect_article" value="0" type="hidden" />
            <input name="collect_article" value="1" type="checkbox"{{ !empty($item->collect_article) || empty($item->id) ? ' checked' : '' }} /> <label>статью</label>
            <input name="collect_video" value="0" type="hidden" />
            <input name="collect_video" value="1" type="checkbox"{{ !empty($item->collect_video) || empty($item->id) ? ' checked' : '' }} /> <label> видео</label>
            <input name="collect_gallery" value="0" type="hidden" />
            <input name="collect_gallery" value="1" type="checkbox"{{ !empty($item->collect_gallery) || empty($item->id) ? ' checked' : '' }} /> <label>галерею</label>
        </div>
    </div>
    <div class="row">
        <dl>Заголовок, Title</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
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