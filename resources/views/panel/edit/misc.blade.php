@extends('panel.edit.form')

@section('input')
    <div class="row">
        <dl>Название</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <!--
    <div class="row">
        <dl>Короткое название для использования в меню</dl>
        <input name="short_name" value="{{ $item->short_name }}" type="text" />
    </div>
    <div class="row">
        <input name="inmenu" value="0" type="hidden" /><label><input name="inmenu" value="1"{{ !empty($item->inmenu) || empty($item->id) ? ' checked' : '' }} type="checkbox" /> показывать в меню</label>
        <input name="a" value="0" type="hidden" /><label><input name="a" value="1"{{ !empty($item->a) || empty($item->id) ? ' checked' : '' }} type="checkbox" /> пункт меню является ссылкой</label>
    </div>
    -->
    @if(!empty($options['misc']) && $options['misc']->count())
        <div class="row">
            <dl>Родительская страница</dl>
            <select name="parent_id">
                <option value="0"></option>
                @foreach($options['misc'] as $miscItem)
                    <option value="{{ $miscItem->id }}"{{ $miscItem->id == $item->parent_id ? ' selected' : '' }}>{{ $miscItem->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="row">
        <dl>Текст</dl>
        <textarea name="text" class="ck">{{ $item->text }}</textarea>
    </div>
    <div class="row">
        <dl>Title</dl>
        <input name="title" value="{{ $item->title }}" type="text" />
    </div>
    <div class="row">
        <dl>Alias (часть URl страницы). Допускается оставить незаполненным для автоматического транслита названия при создании новой записи</dl>
        <input name="alias" value="{{ $item->alias }}" type="text" />
    </div>
    <div class="row">
        <dl>Шаблон</dl>
        <input name="template" value="{{ $item->template }}" type="text" class="x2" />
    </div>
@endsection