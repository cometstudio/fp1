<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;


class IndexController extends Controller
{
    protected $css = 'index';

    public function index(Request $request)
    {
        $diary = Calendar::where('collect_gallery', '=', 1)
            ->join('comments', 'comments.hash', '=', \DB::raw('MD5(CONCAT("gallery_", calendar.id))'), 'LEFT')
            ->where('gallery', '!=', '')
            ->select([
                'calendar.*',
                \DB::raw('COUNT(comments.id) AS comments_total'),
            ])
            ->groupBy([
                'calendar.id'
            ])
            ->orderBy(\DB::raw('RAND()'))
            ->limit(4)
            ->get();

        $mixed = Calendar::where('collect_gallery', '=', 1)->where('gallery', '!=', '')->select(\DB::raw('REPLACE(TRIM(GROUP_CONCAT(gallery)), " ", ",") AS gallery'))->first();

        $mixedGallery = !empty($mixed) ? explode(',', $mixed->gallery) : [];

        shuffle($mixedGallery);

        $mixedGallery = array_slice($mixedGallery, 0, 4);

        $settings = view()->shared('settings');

        $title = $settings->main_page_title;

        return view(
            'index.index', [
                'css'=>$this->css,
                'diary'=>$diary,
                'mixedGallery'=>$mixedGallery,
                'title'=>$title,
            ]
        );
    }
}
