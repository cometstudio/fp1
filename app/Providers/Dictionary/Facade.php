<?php
namespace App\Providers\Dictionary;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dictionary';
    }
}