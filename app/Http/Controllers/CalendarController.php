<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Meal;
use Illuminate\Http\Request;
use Date;
use App\Models\Comment;

class CalendarController extends Controller
{
    protected $css = 'calendar';

    public function index(Request $request)
    {
        $startAt = $request->has('date') ? \Date::getTimeFromDate($request->get('date')) : mktime(0, 0, 0, date('n'), date('j'), date('Y'));

        $seasonDaysLeft = Date::seasonDaysLeft($startAt);

        $calendar = (new Calendar)->day($startAt);

        if(empty($calendar)) {
            $title = 'Дневник';

            return response(
                view('calendar.empty', [
                    'css'=>$this->css,
                    'title'=>$title,
                    'startAt'=>$startAt,
                    'seasonDaysLeft'=>$seasonDaysLeft,
                ]), 404
            );
        }

        $exercises = $calendar->exercises()->get();

        $recipes = $calendar->recipes()->get();

        $totalMacros = $calendar->totalMacros($recipes);

        $mealIds = $recipes->pluck('meal_id');

        $meals = Meal::whereIn('id', $mealIds)->orderBy('ord', 'DESC')->get();

        $commentsHash = (new Comment)->hash($request->segments()[0].'_'.$startAt);

        $title = (!empty($calendar->collect_article) && !empty($calendar->text)) ? $calendar->title : 'Дневник. День '.$seasonDaysLeft;

        return view(
            'calendar.index', [
                'css'=>$this->css,
                'title'=>$title,
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
