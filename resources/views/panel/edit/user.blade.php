@extends('panel.edit.form')

@section('input')
    <div class="row">
        <dl>Имя</dl>
        <input name="name" value="{{ $item->name }}" type="text" />
    </div>
    <div class="row">
        <dl>E-mail</dl>
        <input class="x3" name="email" value="{{ $item->email }}" type="text" />
    </div>
    <div class="row">
        <dl>Пароль</dl>
        <input class="x3" name="_password" value="" type="password" autocomplete="off" />
    </div>

    @if(!empty($options))
        @if(!empty($panelModels))
            <div class="row">
                <dl>Доступные разделы</dl>
                <ul>
                    @foreach($panelModels as $panelModel)
                        <?php
                        $userPanelModel = $options['userPanelModels']->filter(function($userPanelModel) use ($panelModel){ return $userPanelModel->id == $panelModel->id; })->first();
                        $pivot = !empty($userPanelModel->pivot) ? $userPanelModel->pivot : null;
                        ?>
                        <li><input onclick="$(this).parent().find('input').prop('checked', $(this).prop('checked'));" name="_panel_model_ids[]" value="{{ $panelModel->id }}" type="checkbox"{{ $options['userPanelModels']->contains('id', $panelModel->id) ? ' checked' : '' }} /> Allow: <input name="_panel_model_crud[{{ $panelModel->id }}][c]" value="1" type="checkbox" {{ !empty($pivot->c) ? ' checked' : '' }} /> c <input name="_panel_model_crud[{{ $panelModel->id }}][r]" value="1" type="checkbox" {{ !empty($pivot->r) ? ' checked' : '' }} /> r <input name="_panel_model_crud[{{ $panelModel->id }}][u]" value="1" type="checkbox" {{ !empty($pivot->u) ? ' checked' : '' }} /> u <input name="_panel_model_crud[{{ $panelModel->id }}][d]" value="1" type="checkbox" {{ !empty($pivot->d) ? ' checked' : '' }} /> d of {{ $panelModel->name }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
@endsection