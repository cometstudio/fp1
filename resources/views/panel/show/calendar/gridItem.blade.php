<div id="i{{ $item->id }}" class="items">
    @if(!empty($currentPanelModel->sortable))
        <div class="grab col">=</div>
    @endif
    <div class="id col">{{ $item->id }}</div>
    <div class="col">
        <a href="{{ route('admin::act', ['action'=>'edit', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$item->id], false) }}">{{ \Date::getDateFromTime($item->start_at) }}, {{ mb_convert_case(\Date::weekday($item->start_at), MB_CASE_LOWER) }}</a>
    </div>
</div>