<?php

namespace App\Providers\Decryptor;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('decryptor', function ($app) {
            return new Decryptor();
        });
    }
}