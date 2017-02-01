<?php
namespace App\Providers\Telegram;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'telegram';
    }
}