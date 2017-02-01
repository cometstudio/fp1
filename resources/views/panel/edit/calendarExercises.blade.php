@if(!empty($binded))
    <ul style="margin-bottom: 10px;">
        @foreach($binded as $bind)
            <li><a href="{{ route('admin::act', ['action'=>'edit', 'modelName'=>'exercise', 'id'=>$bind->id], false) }}">{{ $bind->name }}</a> ({{ config('exercise_types.'.$bind->pivot->type) }}) <a onclick="return dropBinding(this, $('.calendar-exercises'));" href="{{ route('admin::act', ['action'=>'unbindexercise', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$bind->pivot->calendar_id, 'bid'=>$bind->pivot->id], false) }}">Удалить</a></li>
        @endforeach
    </ul>
@endif