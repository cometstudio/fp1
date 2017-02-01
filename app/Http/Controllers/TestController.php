<?php

namespace App\Http\Controllers;

use Instagram;

class TestController extends Controller
{
    public function index()
    {

        dd(Instagram::follow(3609615257));

        die;
        //$this->dispatch(new SubmitVerificationEmail(Auth::user()));
    }
}
