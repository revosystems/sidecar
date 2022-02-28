<?php

namespace Revo\Sidecar;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;

class CustomReports
{
    public array $saved = [];
    static private $key = 'sidecar_savedReports';

    public static function all() : array {
        return static::redisGet();
    }

    public static function save(string $name, string $url)
    {
        $savedReports = static::all();
        $savedReports[] = ["name" => $name, "url" => $url];
        static::redisSet(json_encode($savedReports));
    }

    public static function delete($name) {
        $savedReports = collect(static::all())->reject(function ($savedReport) use($name) {
            return $savedReport["name"] == $name;
        })->all();
        static::redisSet(json_encode($savedReports));
    }

    public static function key() : string
    {
        if (Sidecar::$usesMultitenant) {
            return collect([static::$key, auth()->id()])->implode("_");
        }
        return static::$key;
    }

    public static function redisGet() : array {
        if (app()->runningUnitTests()) { return []; }
        return json_decode(Redis::get(static::key()), true) ?? [];
    }

    public static function redisSet($value){
        if (app()->runningUnitTests()) { return; }
        Redis::set(static::key(), $value);
    }
}