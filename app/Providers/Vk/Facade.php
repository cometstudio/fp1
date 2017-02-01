<?php
namespace App\Providers\Vk;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vk';
    }
}