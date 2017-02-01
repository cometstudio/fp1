<?php

namespace App\Providers\Date;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('date', function ($app) {
            return new Date();
        });
    }
}