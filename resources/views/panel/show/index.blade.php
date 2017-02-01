@extends('panel.fullLayout')

@section('content')
    @if(!empty($canCreate))
        <h4><a href="{{  route('admin::act', ['action'=>'create', 'modelName'=>$currentPanelModel->public_model_name], false) }}">Создать</a></h4>
    @endif
    <h1>{{ $currentPanelModel->name }}</h1>
    @if(!empty($items))
        <nav class="per-page">
            Показывать на странице: <a href="{{  route('admin::act', ['action'=>'show', 'modelName'=>$currentPanelModel->public_model_name], false) }}">20</a> <a href="{{  route('admin::act', ['action'=>'show', 'modelName'=>$currentPanelModel->public_model_name, null, 'q'=>50], false) }}">50</a> <a href="{{  route('admin::act', ['action'=>'show', 'modelName'=>$currentPanelModel->public_model_name, null, 'q'=>100], false) }}">100</a> <a href="{{  route('admin::act', ['action'=>'show', 'modelName'=>$currentPanelModel->public_model_name, null, 'q'=>-1], false) }}">всё</a>
        </nav>

        @include('panel.filters.'.(view()->exists('panel.filters.'.$currentPanelModel->public_model_name) ? $currentPanelModel->public_model_name : 'common'))

        @if(!empty($currentPanelModel->sortable))
            <input name="_sort_action_url" value="{{ route('admin::act', ['action'=>'sort', 'modelName'=>$currentPanelModel->public_model_name], false) }}" type="hidden" />
        @endif
        <div class="sortable common-grid">
            @foreach($items as $item)
                @include('panel.show.'.(!empty($currentPanelModel->grid_item_view) ? $currentPanelModel->grid_item_view : 'gridItem'))
            @endforeach
        </div>

        @if(!(request('q') < 0))
            {{ $items->appends(request()->all())->links() }}
        @endif
    @endif
@endsection