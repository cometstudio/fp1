<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Meal;
use Illuminate\Http\Request;
use Date;
use App\Models\Comment;
use App\Models\Misc;

class CalendarController extends Controller
{
    protected $css = 'calendar';

    public function index(Request $request)
    {
        $misc = Misc::where('alias', '=', $request->segment(1))->first();

        $startAt = $request->has('date') ? \Date::getTimeFromDate($request->get('date')) : mktime(0, 0, 0, date('n'), date('j'), date('Y'));

        $seasonDaysLeft = Date::seasonDaysLeft($startAt);

        $calendar = (new Calendar)->day($startAt);

        if(empty($calendar)) {
            $title = !empty($misc) ? !empty($misc->title) ? $misc->title : $misc->name : '';

            return response(
                view('calendar.empty', [
                    'css'=>$this->css,
                    'title'=>$title,
                    'startAt'=>$startAt,
                    'seasonDaysLeft'=>$seasonDaysLeft,
                ]), 404
            );
        }

        $calendar->setAttribute('views', $calendar->views+1)->update();

        $exercises = $calendar->exercises()->get();

        $recipes = $calendar->recipes()->get();

        $totalMacros = $calendar->totalMacros($recipes);

        $mealIds = $recipes->pluck('meal_id');

        $meals = Meal::whereIn('id', $mealIds)->orderBy('ord', 'DESC')->get();

        $commentsHash = (new Comment)->hash($request->segments()[0].'_'.$startAt);


        if(!empty($calendar->collect_article) && !empty($calendar->title)){
            $title = $calendar->title;
        }else if(!empty($misc)){
            $title = !empty($misc->title) ? $misc->title : $misc->name.'. День '.$seasonDaysLeft;
        }else{
            $title = '';
        }

        return view(
            'calendar.index', [
                'css'=>$this->css,
                'title'=>$title,
                'misc'=>$misc,
                'startAt'=>$startAt,
                'seasonDaysLeft'=>$seasonDaysLeft,
                'calendar'=>$calendar,
                'exercises'=>$exercises,
                'meals'=>$meals,
                'recipes'=>$recipes,
                'totalMacros'=>$totalMacros,
                'commentsHash'=>$commentsHash,
            ]
        );
    }
}
