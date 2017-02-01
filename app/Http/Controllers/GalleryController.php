<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Date;
use App\Models\Comment;
use App\Models\Misc;

class GalleryController extends Controller
{
    protected $css = 'gallery';

    public function index(Request $request)
    {
        $misc = Misc::where('alias', '=', $request->segment(1))->first();
        $title = !empty($misc) ? !empty($misc->title) ? $misc->title : $misc->name : '';

        $gallery = Calendar::where('collect_gallery', '=', 1)
            ->join('comments', 'comments.hash', '=', \DB::raw('MD5(CONCAT("'.$request->segments()[0].'_", calendar.id))'), 'LEFT')
            ->where('gallery', '!=', '')
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
            'gallery.index', [
                'css'=>$this->css,
                'title'=>$title,
                'misc'=>$misc,
                'gallery'=>$gallery,
            ]
        );
    }
    public function item(Request $request, $id = 0)
    {
        $this->css = 'gallery';

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

        $item->setAttribute('gallery_views', $item->gallery_views+1)->update();

        $seasonDaysLeft = Date::seasonDaysLeft($item->start_at);

        $commentsHash = (new Comment)->hash($request->segments()[0].'_'.$item->id);

        $title = 'День '.$seasonDaysLeft.'. Фотоотчёт';

        return view(
            'gallery.item', [
                'css'=>$this->css,
                'title'=>$title,
                'seasonDaysLeft'=>$seasonDaysLeft,
                'item'=>$item,
                'commentsHash'=>$commentsHash,
            ]
        );
    }
}
