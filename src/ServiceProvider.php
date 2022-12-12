<?php

namespace Revo\Sidecar;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sidecar.php' => config_path('sidecar.php'),
            __DIR__.'/../resources/js/sidecar.js' => resource_path('/js/sidecar.js'),
            __DIR__.'/../resources/css/sidecar.css' => resource_path('/css/sidecar.css'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sidecar');
    }

    public function isDeferred(){
        return false;
    }

    public function provides(){
        return Sidecar::class;
    }
}