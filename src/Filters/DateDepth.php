<?php

namespace Revo\Sidecar\Filters;

use Carbon\Carbon;

class DateDepth
{
    protected $next = [
        "year" => "quarter",
        "quarter" => "month",
        "month" => "week",
        "week" => "day",
        "dayOfWeek" => "day",
        "day" => "hour"
    ];

    public function next($field, $date, $filters) : ?array
    {
        $groupType = $filters->groupBy->groupings[$field] ?? null;
        if ($groupType == null || $groupType == "hour") { return null; }
        return [
            "select" => $field . ":" . $this->next[$groupType],
            "start"  => $this->periodStartFor($date, $groupType),
            "end"   => $this->periodEndFor($date, $groupType)
        ];
    }

    public function periodStartFor(Carbon $date, $period) : string
    {
        if ($period == "year"){ $date->startOfYear()->toDateString(); }
        if ($period == "quarter"){ $date->startOfQuarter()->toDateString(); }
        if ($period == "month"){ $date->startOfMonth()->toDateString(); }
        if ($period == "week"){ $date->startOfWeek()->toDateString(); }
        if ($period == "day"){ $date->startOfDay()->toDateString(); }
        return $date->toDateString();
    }

    public function periodEndFor(Carbon $date, $period): string
    {
        if ($period == "year"){ $date->endOfYear()->toDateString(); }
        if ($period == "quarter"){ $date->endOfQuarter()->toDateString(); }
        if ($period == "month"){ $date->endOfMonth()->toDateString(); }
        if ($period == "week"){ $date->endOfWeek()->toDateString(); }
        if ($period == "day"){ $date->endOfDay()->toDateString(); }
        return $date->toDateString();
    }
}