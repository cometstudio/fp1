@if(!empty($currentUserPanelModels))
    <ul>
        @foreach($currentUserPanelModels as $model)
            <li{!! (request()->segment(count(request()->segments())) == $model->public_model_name) || ($model->custom_url && (request()->getPathInfo() == $model->custom_url)) ? ' class="active"' : '' !!}><a href="{{ !empty($model->custom_url) ? $model->custom_url : route('admin::act', ['action'=>'show', 'modelName'=>$model->public_model_name], false) }}">{{ $model->name }}</a></li>
        @endforeach
    </ul>
@endif
