<?php

namespace App\Providers\Dictionary;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('dictionary', function ($app) {
            return new Dictionary();
        });
    }
}