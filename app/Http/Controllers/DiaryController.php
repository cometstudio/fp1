<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Date;
use App\Models\Comment;
use App\Models\Misc;

class DiaryController extends Controller
{
    protected $css = 'calendar';

    public function index(Request $request)
    {
        $misc = Misc::where('alias', '=', $request->segment(1))->first();
        $title = !empty($misc) ? !empty($misc->title) ? $misc->title : $misc->name : '';

        $builder = Calendar::where(function ($query){
            $query->where('calendar.collect_article', '=', 1)
                ->orWhere('calendar.collect_gallery', '=', 1)
                ->orWhere('calendar.collect_video', '=', 1);
            })
            ->where('calendar.gallery', '!=', '')
            ->join('comments', 'comments.hash', '=', \DB::raw('MD5(CONCAT("'.$request->segments()[0].'_", calendar.id))'), 'LEFT')
            ->select([
                'calendar.*',
                \DB::raw('COUNT(comments.id) AS comments_total'),
            ])
            ->groupBy([
                'calendar.id'
            ])
            ->orderBy('calendar.start_at', 'DESC');

        $diary = $builder->paginate(12);

        return view(
            'diary.index', [
                'css'=>$this->css,
                'title'=>$title,
                'misc'=>$misc,
                'diary'=>$diary,
            ]
        );
    }

    public function item(Request $request, $id)
    {
        $aboutUsSeen = request()->session()->get('about_us_seen', 0);
        if($aboutUsSeen < 2){
            $aboutUsSeen++;
            request()->session()->set('about_us_seen', $aboutUsSeen);
        }

        $misc = Misc::where('alias', '=', $request->segment(1))->first();

        $calendar = (new Calendar)->where('id', $id)->firstOrFail();

        $seasonDaysLeft = Date::seasonDaysLeft($calendar->start_at);

        $calendar->setAttribute('views', $calendar->views+1)->update();

        $commentsHash = (new Comment)->hash('calendar_'.$calendar->start_at);

        if(!empty($calendar->collect_article) && !empty($calendar->title)){
            $title = $calendar->title;
        }else if(!empty($misc)){
            $title = !empty($misc->title) ? $misc->title : $misc->name.'. День '.$seasonDaysLeft;
        }else{
            $title = '';
        }

        return view(
            'diary.item', [
                'css'=>$this->css,
                'title'=>$title,
                'misc'=>$misc,
                'seasonDaysLeft'=>$seasonDaysLeft,
                'calendar'=>$calendar,
                'commentsHash'=>$commentsHash,
            ]
        );
    }
}
