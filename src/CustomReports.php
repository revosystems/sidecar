<?php

namespace Revo\Sidecar;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;

class CustomReports
{
    public array $saved = [];
    static private $key = 'sidecar_savedReports';

    public static function all() : array {
        return json_decode(Redis::get(static::key()), true) ?? [];
    }

    public static function save(string $name, string $url)
    {
        $savedReports = static::all();
        $savedReports[] = ["name" => $name, "url" => $url];
        Redis::set(static::key(), json_encode($savedReports));
    }

    public static function delete($name) {
        $savedReports = collect(static::all())->reject(function ($savedReport) use($name) {
            return $savedReport["name"] == $name;
        })->all();
        Redis::set(static::key(), json_encode($savedReports));
    }

    public static function key() : string
    {
        return collect([static::$key, auth()->id()])->implode("_");
    }


}