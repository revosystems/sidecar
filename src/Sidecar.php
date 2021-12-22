<?php

namespace Revo\Sidecar;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Sidecar
{
    protected static $servingCallback;
    public static $usesMultitenant = true;

    public function __construct()
    {
        if (static::$servingCallback) {
            call_user_func(static::$servingCallback);
        }
    }

    static public function make($name) : ?Report
    {
        $path = config('sidecar.reportsPath') . $name;
        if (!class_exists($path)) {
            throw new ModelNotFoundException("Report not found");
        }
        return new $path;
    }

    public static function serving($servingCallback)
    {
        static::$servingCallback = $servingCallback;
    }

    public static function chartJs()
    {
        return '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>';
    }
}