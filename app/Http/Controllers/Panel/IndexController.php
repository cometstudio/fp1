<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PanelModel;

class IndexController extends Controller
{
    public function index()
    {
        $panelModels = PanelModel::all();

        return view('panel.index.index', compact('panelModels'));
    }
}
