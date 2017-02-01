<?php
namespace App\Providers\Decryptor;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'decryptor';
    }
}