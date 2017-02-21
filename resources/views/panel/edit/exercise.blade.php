@extends('panel.edit.form')

@section('input')
    <div class="row">
        <dl>Название</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <div class="row">
        <dl>Текст</dl>
        <textarea name="text" class="ck">{{ $item->text }}</textarea>
    </div>
    <div class="row">
        <dl>Количество повсторений в pump-сетах</dl>
        <input name="notice" value="{{ $item->notice }}" type="text" />
    </div>
    <div class="row">
        <dl>Количество повсторений в сетах, классика</dl>
        <input name="notice1" value="{{ $item->notice1 }}" type="text" />
    </div>
    <div class="row">
        <dl>Количество повсторений в сетах на силу</dl>
        <input name="notice2" value="{{ $item->notice2 }}" type="text" />
    </div>

@endsection