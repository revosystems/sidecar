<?php

namespace Revo\Sidecar\Filters;

use App\Models\EloquentBuilder;
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

    /*public function group($query) : EloquentBuilder {
        $this->groupings->each(function ($type, $key) use($query) {
           $this->groupBy($query, $key, $type);
        });
        return $query;
    }*/

    public function groupBy($query, $key, $type) : EloquentBuilder
    {
        if ($key == null) { return $query; }
        if ($type == 'hour') {
            return $query->groupBy(DB::raw("hour({$key})"))
                /*->orderBy(DB::raw('hour(' . subTime($key, Date::$openingTime) . ')'), 'ASC')*/;
        }
        if ($type == 'day') {
            return $query->groupBy(DB::raw('date(' . subTime($key, Date::$openingTime) . ')'))
                         /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'dayOfWeek') {
            return $query->groupBy(DB::raw('dayofweek(' . subTime($key, Date::$openingTime) . ')'));
        }
        if ($type == 'week') {
            return $query->groupBy(DB::raw('yearweek(' . subTime($key, Date::$openingTime) . ')'))
                           /*->groupBy(DB::raw('year(' . subTime($key, Date::$openingTime) . ')'))*/
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'month') {
            return $query->groupBy(DB::raw('month(' . subTime($key, Date::$openingTime) . ')'))
                          ->groupBy(DB::raw('year(' . subTime($key, Date::$openingTime) . ')'))
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        if ($type == 'quarter') {
            return $query->groupBy(DB::raw('quarter(' . subTime($key, Date::$openingTime) . ')'))
                          ->groupBy(DB::raw('year(' . subTime($key, Date::$openingTime) . ')'))
                          /*->orderBy(DB::raw($key), 'DESC')*/;
        }
        return $query->groupBy(DB::raw($key));
    }
}