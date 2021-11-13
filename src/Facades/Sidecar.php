<?php

class Sidecar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Revo\Sidecar\Sidecar::class;
    }

    public static function serving($callback)
    {
        Revo\Sidecar\Sidecar::serving($callback);
    }
}