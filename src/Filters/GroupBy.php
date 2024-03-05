<?php

namespace Revo\Sidecar\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Revo\Sidecar\ExportFields\Date;

class GroupBy
{
    public $groupings;

    public function __construct(?array $groupings) {
        $this->groupings = collect($groupings)->mapWithKeys(function($value){
            list($key, $type) = explode(":", $value);
            return [$key => $type];
        });
    }

    public function isGrouping() : bool {
        return !$this->groupings->isEmpty();
    }

    public function canBeCompared()
    {
        return $this->groupings->count() == 1;
    }

    public function isGroupingBy($key, $type = null) : bool {
        if ($type == null) {
            return array_key_exists($key, $this->groupings->all());
        }
        return isset($this->groupings[$key]) && $this->groupings[$key] == $type;
    }

    /*public function group($query) : Builder {
        $this->groupings->each(function ($type, $key) use($query) {
           $this->groupBy($query, $key, $type);
        });
        return $query;
    }*/

    public function groupBy($query, $key, $type) : Builder
    {
        if ($key == null) { return $query; }
        if ($type == 'hour') {
            return $query->groupBy(DB::raw("hour({$key})"))
                /*->orderBy(DB::raw('hour(' . $this->>subTime($key, Date::getUtcOpeningTime()) . ')'), 'ASC')*/;
        }
        if ($type == 'day') {
            return $query->groupBy(DB::raw('date(' . $this->businessTime($key) . ')'))
                         /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'dayOfWeek') {
            return $query->groupBy(DB::raw('dayofweek(' . $this->businessTime($key) . ')'));
        }
        if ($type == 'week') {
            return $query->groupBy(DB::raw('yearweek(' . $this->businessTime($key) . ')'))
                           /*->groupBy(DB::raw('year(' . $this->>subTime($key, Date::getUtcOpeningTime()) . ')'))*/
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'month') {
            return $query->groupBy(DB::raw('month(' . $this->businessTime($key) . ')'))
                          ->groupBy(DB::raw('year(' . $this->businessTime($key) . ')'))
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'quarter') {
            return $query->groupBy(DB::raw('quarter(' . $this->businessTime($key) . ')'))
                          ->groupBy(DB::raw('year(' . $this->businessTime($key) . ')'))
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        return $query->groupBy(DB::raw($key));
    }

    function businessTime($field)
    {
        $timezone = Date::$timezone;
        $openingTime = Date::$openingTime;
        return "SUBTIME(CONVERT_TZ({$field}, 'UTC', '{$timezone}'), '{$openingTime}')";
    }
}