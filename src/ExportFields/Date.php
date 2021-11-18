<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
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
        if ($type == 'hour')     { return $date->format('H:00'); }
        if ($type == 'day')      { return $date->format('d M Y'); }
        if ($type == 'dayOfWeek'){ return $date->format('l'); }//dayOfWeek; }
        if ($type == 'week')    { return $date->format('W (M Y)'); }
        if ($type == 'month')    { return $date->format('M Y'); }
        if ($type == 'quarter')    { return "Quarter " . ceil($date->month/3) . ' '. $date->format('Y'); }
        return $date->toDateString();
    }

    protected function getCarbonDate($value) : Carbon {
        return Carbon::parse($value)->timezone(static::$timezone);
    }

    public function groupings() : array
    {
        return ['hour', 'day', 'dayOfWeek', 'week', 'month', 'quarter'];
    }

    public function applyFilter(Filters $filters, EloquentBuilder $query, $key, $values) : EloquentBuilder
    {
        return $filters->applyDateFilter($query, $this->databaseTable().'.'.$key, $values);
    }
}