<?php

namespace Revo\Sidecar;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Revo\Sidecar\ExportFields\Currency;
use Revo\Sidecar\ExportFields\Decimal;

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

    public static function setFormatter(string $locale, string $currency = 'EUR')
    {
        Decimal::setFormatter($locale);
        Currency::setFormatter($locale, $currency ?? 'EUR');
    }

    public static function make($name) : ?Report
    {
        $path = config('sidecar.reportsPath') . $name;
        if (! class_exists($path)) {
            throw new ModelNotFoundException('Report not found');
        }
        return new $path;
    }

    public static function serving($servingCallback)
    {
        static::$servingCallback = $servingCallback;
    }

    public static function dependencies()
    {
        return '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css"/>
            <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>';
    }
}
