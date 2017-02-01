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
    <div class="row">
        <dl>Заголовок, Title</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <div class="row">
        <dl>Текст</dl>
        <textarea name="text" class="ck">{{ $item->text }}</textarea>
    </div>
@endsection