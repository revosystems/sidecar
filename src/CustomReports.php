<?php

namespace Revo\Sidecar;

use Illuminate\Support\Facades\Cookie;

class CustomReports
{
    public array $saved = [];
    static private $key = 'sidecar_savedReports';

    public static function save(string $name, string $url)
    {
        $savedReports = static::all();
        $savedReports[] = ["name" => $name, "url" => $url];
        Cookie::queue(static::$key, json_encode($savedReports), 60 * 24 * 365 * 10); //minutes
    }

    public static function all() : array {
        return json_decode(Cookie::get(static::$key), true) ?? [];
    }
}