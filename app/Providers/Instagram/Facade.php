<?php
namespace App\Providers\Instagram;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'instagram';
    }
}