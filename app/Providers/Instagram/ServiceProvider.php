<?php

namespace App\Providers\Instagram;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind('instagram', function ($app) {
            return new Instagram();
        });
    }
}