<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /** @var Application $app */
        $app = require __DIR__.'/../laravel.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }
}