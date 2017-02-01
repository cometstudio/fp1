@extends('panel.edit.form')

@section('input')
    @if(!empty($item->sent_at))
        <div class="small font row">
            Отправлено подписчикам <b>{{ \Date::getDateFromTime($item->sent_at, 3) }}</b>
        </div>
    @endif
    @if(!$item->queued)
        <div class="row">
            <input name="queued" value="0" type="hidden" />
            <input name="queued" value="1" type="checkbox"{{ $item->queued ? ' checked' : '' }}{{ !empty($item->queued) ? ' disabled' : '' }} /> <label>разослать</label>
        </div>
    @else
        <div class="small font row">
            Поставлено в очередь на рассылку
        </div>
    @endif
    <div class="row">
        <dl>Заголовок</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <div class="row">
        <dl>Текст</dl>
        <textarea name="text" class="ck">{{ $item->text }}</textarea>
    </div>
@endsection