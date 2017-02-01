<?php

namespace App\Providers\Vk;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind('vk', function ($app) {
            return new Vk();
        });
    }
}