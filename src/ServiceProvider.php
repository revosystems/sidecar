<?php

namespace Revo\Sidecar;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/sidecar.php' => config_path('sidecar.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sidecar');
    }

    public function isDeferred(){
        return false;
    }

    public function provides(){
        return "Sidecar";
    }
}