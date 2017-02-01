<?php

namespace App\Http\Controllers;

use App\Models\Supplement;
use Illuminate\Http\Request;
use App\Models\Misc;
use App\Models\Calendar;


class SupplementsController extends Controller
{
    protected $css = 'supplements';

    public function index(Request $request)
    {
        $misc = Misc::where('alias', '=', $request->segment(2))->first();
        $title = !empty($misc) ? !empty($misc->title) ? $misc->title : $misc->name : '';

        $orderBy = $request->has('ob') ? $request->get('ob') : 'name';

        $supplementsBuilder = Supplement::orderBy($orderBy, $request->has('o') ? 'DESC' : 'ASC');

        if($request->has('q')) $supplementsBuilder->where('name', 'LIKE', '%'. $request->get('q') .'%');

        $supplements = $supplementsBuilder->get();

        return view(
            'supplements.index', [
                'css'=>$this->css,
                'title'=>$title,
                'misc'=>$misc,
                'supplements'=>$supplements,
            ]
        );
    }

    public function graph(Request $request)
    {
        $misc = Misc::where('alias', '=', $request->segment(2))->first();
        $title = !empty($misc) ? !empty($misc->title) ? $misc->title : $misc->name : '';

        $startAt = $request->has('date') ? \Date::getTimeFromDate($request->get('date')) : time();

        $segment= Calendar::where('start_at', '>', ($startAt-(86400*14)))
            ->where('start_at', '<=', $startAt)
            ->orderBy('start_at', 'ASC')
            ->get();

        $maxValue = [];
        $graphData = [];
        $macrosConfig = config('macros', []);
        foreach($segment as $id=>$calendar){
            $graphData[$id] = $calendar->totalMacros();
            $graphData[$id]['calendar'] = $calendar;
            if(!empty($macrosConfig['months'][0])){
                foreach($macrosConfig['months'][0] as $type=>$value){
                    if(empty($maxValue[$type]) || $graphData[$id][$type.'_daily']['value'] > $maxValue[$type]){
                        $maxValue[$type] = $graphData[$id][$type.'_daily']['value'];
                        if($graphData[$id][$type.'_daily']['total'] > 100) $maxValue[$type] *= $graphData[$id][$type.'_daily']['total'] / 100;
                    }
                }
            }
        }

        return view(
            'supplements.graph', [
                'css'=>$this->css,
                'title'=>$title,
                'misc'=>$misc,
                'startAt'=>$startAt,
                'graphData'=>$graphData,
                'type'=>$type,
                'maxValue'=>$maxValue,
            ]
        );
    }
}
