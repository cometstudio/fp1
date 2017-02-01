<div id="i{{ $item->id }}" class="items">
    @if(!empty($currentPanelModel->sortable))
        <div class="grab col">=</div>
    @endif
    <div class="id col">{{ $item->id }}</div>
    @if(!empty($currentPanelModel->has_gallery))
        <div class="thumb col"><img src="{{ $imagesPath }}/thumbs/{{ $item->getThumbnail() }}.jpg" /></div>
    @endif
    <div class="col">
        <a href="{{ route('admin::act', ['action'=>'edit', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$item->id], false) }}">{{ !empty($item->name) ? $item->name : 'Undefined' }}</a>
    </div>
</div>