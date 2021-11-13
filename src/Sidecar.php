<?php

namespace Revo\Sidecar;

class Sidecar
{

    protected static $servingCallback;

    public function __construct()
    {
        if (static::$servingCallback) {
            call_user_func(static::$servingCallback);
        }
    }

    static public function make($name) : ?Report
    {
        $path = "\\App\\Reports\\V2\\{$name}";
        return new $path;
    }

    public static function serving($servingCallback)
    {
        static::$servingCallback = $servingCallback;
    }
}