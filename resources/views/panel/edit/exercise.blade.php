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
        <textarea name="notice" class="ck">{{ $item->notice }}</textarea>
    </div>
    <div class="row">
        <dl>Количество повсторений в сетах, классика</dl>
        <textarea name="notice1" class="ck">{{ $item->notice1 }}</textarea>
    </div>
    <div class="row">
        <dl>Количество повсторений в сетах на силу</dl>
        <textarea name="notice2" class="ck">{{ $item->notice2 }}</textarea>
    </div>

@endsection