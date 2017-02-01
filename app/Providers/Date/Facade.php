<?php
namespace App\Providers\Date;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'date';
    }
}