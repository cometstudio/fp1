@if(!empty($binded))
    <ul style="margin-bottom: 10px;">
        @foreach(array_unique($binded->pluck('meal_name')->toArray()) as $mealName)
            <li style="margin-top: 10px;"><b>{{ $mealName }}</b></li>
            @foreach($binded->filter(function($binding) use ($mealName){ return $binding->meal_name == $mealName; }) as $binding)
                <li><a href="{{ route('admin::act', ['action'=>'edit', 'modelName'=>'recipe', 'id'=>$binding->id], false) }}">{{ $binding->name }}</a> <a onclick="return dropBinding(this, $('.calendar-recipes'));" href="{{ route('admin::act', ['action'=>'unbindrecipe', 'modelName'=>$currentPanelModel->public_model_name, 'id'=>$binding->pivot->calendar_id, 'bid'=>$binding->pivot->id], false) }}">Удалить</a></li>
            @endforeach
        @endforeach
    </ul>
    <?php $totalMacros = $item->totalMacros($binded); ?>
    Белок: <b{{ !$totalMacros['protein_daily']['active'] ? ' style="color: green;"' : '' }}>{{ $totalMacros['protein_daily']['total'] }}%</b>, жиры: <b{{ !$totalMacros['fat_daily']['active'] ? ' style="color: green;"' : '' }}>{{ $totalMacros['fat_daily']['total'] }}%</b>, углеводы: <b{{ !$totalMacros['carbohydrates_daily']['active'] ? ' style="color: green;"' : '' }}>{{ $totalMacros['carbohydrates_daily']['total'] }}%</b>, энергия: <b{{ !$totalMacros['energy_daily']['active'] ? ' style="color: green;"' : '' }}>{{ $totalMacros['energy_daily']['total'] }}%</b> от дневной нормы
@endif