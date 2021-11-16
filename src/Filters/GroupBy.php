<?php

namespace Revo\Sidecar\Filters;

use App\Models\EloquentBuilder;
use Illuminate\Support\Facades\DB;

class GroupBy
{
    public $groupings;

    public static $openingTime = "00:00";

    public function __construct(?array $groupings) {
        $this->groupings = collect($groupings)->mapWithKeys(function($value){
            list($key, $type) = explode(":", $value);
            return [$key => $type];
        });
    }

    public function isGrouping() : bool {
        return !$this->groupings->isEmpty();
    }

    public function isGroupingBy($key, $type = null) : bool {
        if ($type == null) {
            return array_key_exists($key, $this->groupings->all());
        }
        return isset($this->groupings[$key]) && $this->groupings[$key] == $type;
    }

    public function group($query) : EloquentBuilder {
        $this->groupings->each(function ($type, $key) use($query) {
           $this->groupBy($query, $key, $type);
        });
        return $query;
    }

    private function groupBy($query, $key, $type) : EloquentBuilder
    {
        if ($key == null) { return $query; }

        if ($type == 'day') {
            return $query->groupBy(DB::raw('date(' . subTime($key, static::$openingTime) . ')'))->orderBy($key, 'DESC');
        }
        if ($type == 'week') {
            return $query->groupBy(DB::raw('week(' . subTime($key, static::$openingTime) . ')'))->groupBy(DB::raw('year(' . subTime($key, static::$openingTime) . ')'))->orderBy($key, 'DESC');
        }
        if ($type == 'month') {
            return $query->groupBy(DB::raw('month(' . subTime($key, static::$openingTime) . ')'))->groupBy(DB::raw('year(' . subTime($key, static::$openingTime) . ')'))->orderBy($key, 'DESC');
        }
        if ($type == 'hour') {
            return $query->groupBy(DB::raw("hour({$key})"))->orderBy(DB::raw('hour(' . subTime($key, static::$openingTime) . ')'), 'ASC');
        }
        if ($type == 'dayOfWeek') {
            return $query->groupBy(DB::raw('dayofweek(' . subTime($key, static::$openingTime) . ')'));
        }
        return $query->groupBy($key);
    }
}