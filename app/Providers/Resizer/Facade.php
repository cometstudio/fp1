<?php
namespace App\Providers\Resizer;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'resizer';
    }
}