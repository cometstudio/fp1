<?php

namespace App\Providers\Telegram;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind('telegram', function ($app) {
            return new Telegram();
        });
    }
}