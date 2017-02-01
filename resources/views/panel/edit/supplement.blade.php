@extends('panel.edit.form')

@section('input')
    <div class="row">
        <dl>Название</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <div class="row">
        <dl>На 100 грамм сырого продукта: белок/жиры/углеводы/килокалории</dl>
        <input name="protein" value="{{ $item->protein }}" type="text" style="width:23.5%;margin-right: 2%;" /><input name="fat" value="{{ $item->fat }}" type="text" style="width:23.5%;margin-right: 2%;" /><input name="carbohydrates" value="{{ $item->carbohydrates }}" type="text" style="width:23.5%;margin-right: 2%;" /><input name="energy" value="{{ $item->energy }}" type="text" style="width:23.5%;" />
    </div>
@endsection