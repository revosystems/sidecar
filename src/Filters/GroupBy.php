<?php

namespace Revo\Sidecar\Filters;

use Illuminate\Support\Facades\DB;

class GroupBy
{
    protected $openingTime = "00:00";

    public function groupBy($query, $key, $type)
    {
        if ($key == null) { return $query; }

        if ($type == 'day') {
            return $query->groupBy(DB::raw('date(' . subTime($key, $this->openingTime) . ')'))->orderBy($key, 'DESC');
        }
        if ($type == 'month') {
            return $query->groupBy(DB::raw('month(' . subTime($key, $this->openingTime) . ')'))->groupBy(DB::raw('year(' . subTime($key, $this->openingTime) . ')'))->orderBy($key, 'DESC');
        }
        if ($type == 'hour') {
            return $query->groupBy(DB::raw("hour({$key})"))->orderBy(DB::raw('hour(' . subTime($key, $this->openingTime) . ')'), 'ASC');
        }
        if ($type == 'dayOfWeek') {
            return $query->groupBy(DB::raw('dayofweek(' . subTime($key, $this->openingTime) . ')'));
        }
        return $query->groupBy($key);
    }
}