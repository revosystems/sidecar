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
                /*->orderBy(DB::raw('hour(' . $this->>subTime($key, Date::$openingTime) . ')'), 'ASC')*/;
        }
        if ($type == 'day') {
            return $query->groupBy(DB::raw('date(' . $this->subTime($key, Date::$openingTime) . ')'))
                         /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'dayOfWeek') {
            return $query->groupBy(DB::raw('dayofweek(' . $this->subTime($key, Date::$openingTime) . ')'));
        }
        if ($type == 'week') {
            return $query->groupBy(DB::raw('yearweek(' . $this->subTime($key, Date::$openingTime) . ')'))
                           /*->groupBy(DB::raw('year(' . $this->>subTime($key, Date::$openingTime) . ')'))*/
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'month') {
            return $query->groupBy(DB::raw('month(' . $this->subTime($key, Date::$openingTime) . ')'))
                          ->groupBy(DB::raw('year(' . $this->subTime($key, Date::$openingTime) . ')'))
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'quarter') {
            return $query->groupBy(DB::raw('quarter(' . $this->subTime($key, Date::$openingTime) . ')'))
                          ->groupBy(DB::raw('year(' . $this->subTime($key, Date::$openingTime) . ')'))
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        return $query->groupBy(DB::raw($key));
    }

    function subTime($field, $time)
    {
        return "SUBTIME({$field}, '{$time}')";
    }
}