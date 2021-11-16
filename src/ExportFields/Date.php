<?php

namespace Revo\Sidecar\ExportFields;

use Carbon\Carbon;
use Revo\Sidecar\Filters\Filters;

class Date extends ExportField
{
    static $timezone = "Europe/Madrid";
    public ?string $icon = 'calendar';

    public function getValue($row)
    {
        $value = parent::getValue($row);

        if (! $value || $value == "--") {
            return "--";
        }
        $filters = (new Filters());
        if ($filters->groupBy && $filters->groupBy->isGroupingBy($this->field)){
            return $this->showAs($this->getCarbonDate($value), $filters->groupBy->groupings[$this->field]);
        }
        return $this->getNonGroupedValue($value);
    }

    public function getNonGroupedValue($value) : string {
        return $this->getCarbonDate($value)->toDateString();
    }

    protected function showAs(Carbon $date, $type){
        if ($type == 'day')      { return $date->format('d M Y'); }
        if ($type == 'dayOfWeek'){ return $date->format('l'); }//dayOfWeek; }
        if ($type == 'month')    { return $date->format('M Y'); }
        if ($type == 'hour')     { return $date->format('H:00'); }
        return $date->toDateString();
    }

    protected function getCarbonDate($value) : Carbon {
        return Carbon::parse($value)->timezone(static::$timezone);
    }

    public function groupings() : array
    {
        return ['day', 'month', 'hour', 'dayOfWeek'];
    }

    public function filterStart()
    {
        return request($this->getSelectField())['start'] ?? "";
    }

    public function filterEnd()
    {
        request($this->getSelectField())['end'] ?? "";
    }
}