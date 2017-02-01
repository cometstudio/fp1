@extends('panel.fullLayout')

@section('content')
    <div class="edit form">
        <h4><a href="{{ route('admin::act', ['action'=>'show', 'modelName'=>$currentPanelModel->public_model_name], false) }}">{{ $currentPanelModel->name }}</a></h4>
        @if(!empty($item->name))
            <h1>{{ $item->name or '' }}</h1>
        @endif
        @if(!empty($canUpdate))
            <div class="row">
                <button onclick="return save();" class="button">Сохранить</button>
            </div>
        @endif
        <form action="{{ route('admin::act', ['action'=>'save', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>(!empty($item->id) ? $item->id : null)], false) }}" method="post">

            @yield('input')

            @if(!empty($currentPanelModel->has_gallery))
                <div class="row">
                    @include('panel.edit._gallery')
                </div>
            @endif

            <div class="clearfix">
                @if(!empty($canUpdate))
                    <button onclick="return save();" class="button" style="float: left;">Сохранить</button>
                @endif
                @if(!empty($item->id) && !empty($canDelete))
                    <a href="{{ route('admin::act', ['action'=>'drop', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$item->id], false) }}" onclick="return drop(this);" class="empty black button" style="float: right;">Удалить</a>
                @endif
            </div>

        </form>
        @if(!empty($item->created_at->timestamp) || !empty($item->updated_at->timestamp))
            <div class="date-info mini section">
                @if(!empty($item->created_at->timestamp))
                    <dl>Дата создания: {{ $item->created_at->format('d.m.Y H:i') }}</dl>
                @endif
                @if(!empty($item->updated_at->timestamp))
                    <dl>Дата изменения: {{ $item->updated_at->format('d.m.Y H:i') }}</dl>
                @endif
            </div>
        @endif
    </div>
@endsection