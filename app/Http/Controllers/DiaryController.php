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

        $builder = Calendar::where('collect_gallery', '=', 1)
            ->join('comments', 'comments.hash', '=', \DB::raw('MD5(CONCAT("'.$request->segments()[0].'_", calendar.id))'), 'LEFT')
            ->where('gallery', '!=', '')
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
}
