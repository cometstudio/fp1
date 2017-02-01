<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use Date;
use App\Models\Comment;
use App\Models\Misc;

class VideosController extends Controller
{
    protected $css = 'videos';

    public function index(Request $request)
    {
        $misc = Misc::where('alias', '=', $request->segment(1))->first();
        $title = !empty($misc) ? !empty($misc->title) ? $misc->title : $misc->name : '';

        $videos = Calendar::where('collect_video', '=', 1)
            ->join('comments', 'comments.hash', '=', \DB::raw('MD5(CONCAT("'.$request->segments()[0].'_", calendar.id))'), 'LEFT')
            ->where('video', '!=', '')
            ->select([
                'calendar.*',
                \DB::raw('COUNT(comments.id) AS comments_total'),
            ])
            ->groupBy([
                'calendar.id'
            ])
            ->orderBy('calendar.start_at', 'DESC')
            ->get();

        return view(
            'videos.index', [
                'css'=>$this->css,
                'title'=>$title,
                'misc'=>$misc,
                'videos'=>$videos,
            ]
        );
    }
    public function item(Request $request, $id = 0)
    {
        $this->css = 'video';

        $item = Calendar::where('calendar.id', '=', $id)
            ->join('comments', 'comments.hash', '=', \DB::raw('MD5(CONCAT("'.$request->segments()[0].'_", calendar.id))'), 'LEFT')
            ->select([
                'calendar.*',
                \DB::raw('COUNT(comments.id) AS comments_total'),
            ])
            ->groupBy([
                'calendar.id'
            ])
            ->firstOrFail();

        $item->setAttribute('video_views', $item->video_views+1)->update();

        $seasonDaysLeft = Date::seasonDaysLeft($item->start_at);

        $commentsHash = (new Comment)->hash($request->segments()[0].'_'.$item->id);

        $title = 'День '.$seasonDaysLeft.'. Видеоотчёт';

        return view(
            'videos.item', [
                'css'=>$this->css,
                'title'=>$title,
                'seasonDaysLeft'=>$seasonDaysLeft,
                'item'=>$item,
                'commentsHash'=>$commentsHash,
            ]
        );
    }
}
