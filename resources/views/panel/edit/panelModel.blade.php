@extends('panel.edit.form')

@section('input')
    <div class="row">
        <dl>Название модели</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <div class="row">
        <dl>Alias модели</dl>
        <input name="public_model_name" value="{{ $item->public_model_name }}" type="text" />
    </div>
    <div class="row">
        <dl>Grid item view</dl>
        <input name="grid_item_view" value="{{ $item->grid_item_view }}" type="text" />
    </div>
    <div class="row">
        <dl>Custom URL</dl>
        <input name="custom_url" value="{{ $item->custom_url }}" type="text" />
    </div>
    <div class="row">
        <input name="sortable" value="0" type="hidden" />
        <input name="sortable" value="1" type="checkbox"{{ !empty($item->sortable) ? ' checked' : '' }} /> <label>sortable</label>
    </div>
    <div class="row">
        <input name="has_gallery" value="0" type="hidden" />
        <input name="has_gallery" value="1" type="checkbox"{{ !empty($item->has_gallery) ? ' checked' : '' }} /> <label>has gallery</label>
    </div>
@endsection