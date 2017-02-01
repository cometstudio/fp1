@if(!empty($binded))
    <ul style="margin-bottom: 10px;">
        @foreach($binded as $bind)
            <li><a href="{{ route('admin::act', ['action'=>'edit', 'modelName'=>'supplement', 'id'=>$bind->id], false) }}">{{ $bind->name }}</a>{{ !empty($bind->weight) ? ', '.$bind->weight.' г' : '' }} <a onclick="return dropBinding(this, $('.recipe-supplements'));" href="{{ route('admin::act', ['action'=>'unbindsupplement', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$bind->pivot->recipe_id, 'bid'=>$bind->pivot->id], false) }}">Удалить</a></li>
        @endforeach
    </ul>
@endif