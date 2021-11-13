<?php

namespace Revo\Sidecar\Facades;
use Illuminate\Support\Facades\Facade;
use Revo\Sidecar\Sidecar as SidecarManager;

class Sidecar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SidecarManager::class;
    }

    public static function serving($callback)
    {
        SidecarManager::serving($callback);
    }
}