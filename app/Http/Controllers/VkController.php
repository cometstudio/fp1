<?php

namespace App\Http\Controllers;

use Vk;
use Illuminate\Http\Request;

class VkController extends Controller
{
    public function auth(Request $request)
    {
        return response()->redirectToRoute('admin::act', [
            'action'=>'edit',
            'modelName'=>'settings',
            'id'=>1,
        ]);
    }

    public function test(Request $request)
    {

    }


}