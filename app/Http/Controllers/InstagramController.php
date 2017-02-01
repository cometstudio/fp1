<?php

namespace App\Http\Controllers;

use Instagram;
use Illuminate\Http\Request;

class InstagramController extends Controller
{
    public function auth(Request $request)
    {
        Instagram::auth($request);

        return response()->redirectToRoute('admin::act', [
            'action'=>'edit',
            'modelName'=>'settings',
            'id'=>1,
        ]);
    }

    public function test(Request $request)
    {
        $res = (new Instagram())->like('1420547202161685232_1139664986');

        dd($res);
    }


}