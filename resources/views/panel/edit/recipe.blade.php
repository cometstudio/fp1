@extends('panel.edit.form')

@section('input')
    <div class="row">
        <dl>Название</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <div class="row">
        <dl>Способ приготовления</dl>
        <textarea name="text" class="ck">{{ $item->text }}</textarea>
    </div>
    <div class="row">
        <dl>Состав</dl>
        <textarea name="notice" class="ck">{{ $item->notice }}</textarea>
    </div>
    @if($item->id)
        <div class="row" style="padding-bottom: 10px;">
            @if(!empty($options['supplements']))
                <div style="margin-bottom: 10px;">
                    <dl>Продукт, вес (грамм)</dl>
                    <select name="_supplement_id">
                        <option value="">продукт...</option>
                        @foreach($options['supplements'] as $supplement))
                        <option value="{{ $supplement->id }}">{{ str_limit($supplement->name, 65) }}</option>
                        @endforeach
                    </select> <input name="_weight" type="text" maxlength="4" style="width: 100px;" /> <a onclick="return addBinding(this, $('.recipe-supplements'));" href="{{ route('admin::act', ['action'=>'bindsupplement', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$item->id], false) }}" class="empty button">Добавить</a>

                </div>
            @endif
            <div class="recipe-supplements">
                @include('panel.edit.recipeSupplements', ['binded'=>$options['recipeSupplements']])
            </div>
        </div>
    @endif
    <div class="row">
        <dl>Размер порции</dl>
        <textarea name="size" class="ck">{{ $item->size }}</textarea>
    </div>
@endsection