<?php

namespace App\Providers\Resizer;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind('resizer', function ($app) {
            return new Resizer();
        });
    }
}