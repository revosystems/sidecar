<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;
use Revo\Sidecar\Filters\DateDepth;

class Date extends ExportField
{
    static $timezone = 'Europe/Madrid';
    static $openingTime = '00:00:00';
    public ?string $icon = 'calendar';
    public $timeFilterable = false;

    public function getValue($row)
    {
        return $this->getCarbonDate(parent::getValue($row));
    }

    protected function getCarbonDate($value) : Carbon {
        return Carbon::parse($value)->timezone(static::$timezone);
    }

    public function toHtml($row) : string
    {
        $date = $this->getValue($row);
        if (! $date || $date == '--') {
            return '--';
        }
        if ($this->isGrouped()){
            return $this->showAs($date, (new Filters())->groupBy->groupings[$this->field]);
        }
        return $this->nonGroupedValue($date, forHtml:true);
    }

    public function toCsv($row)
    {
        $date = $this->getValue($row);
        if (! $date || $date == '--') {
            return '--';
        }
        if ($this->isGrouped()){
            return $this->showAs($date, (new Filters())->groupBy->groupings[$this->field]);
        }
        return $this->nonGroupedValue($date, forHtml:false);
    }

    protected function isGrouped() : bool {
        $filters = (new Filters());
        return $filters->groupBy && $filters->groupBy->isGroupingBy($this->field);
    }

    protected function nonGroupedValue($date, $forHtml = false) : string {
        return $date->toDateString();
    }

    public function timeFilterable($timeFilterable = true) : self
    {
        $this->timeFilterable = $timeFilterable;
        return $this;
    }

    protected function showAs(Carbon $date, $type){
        if ($type == 'hour')      { return $date->format('H:00'); }
        if ($type == 'day')       { return $date->format('d M Y'); }
        if ($type == 'dayOfWeek') { return $date->format('l'); }//dayOfWeek; }
        if ($type == 'week')      { return $date->format('W (M Y)'); }
        if ($type == 'month')     { return $date->format('M Y'); }
        if ($type == 'quarter')   { return "Quarter " . ceil($date->month/3) . ' '. $date->format('Y'); }
        if ($type == 'year')      { return $date->format('Y'); }
        return $date->toDateString();
    }

    public function groupings() : array
    {
        return ['hour', 'day', 'dayOfWeek', 'week', 'month', 'quarter', 'year'];
    }

    public function applyFilter(Filters $filters, Builder $query, $key, $values) : Builder
    {
        if (!$this->filterable) { return $query; }
        $businessRange = static::businessRange($values['start'], $values['end']);
        session()->put('sidecar.date', $values);
        if (!Str::contains($key, config('database.connections.mysql.prefix'))){
            return $filters->applyTimeFilter($query, $this->databaseTable().'.'.$key, $values, $businessRange, static::$timezone);
        }
        return $filters->applyTimeFilter($query, str_replace(config('database.connections.mysql.prefix'), "", $key), $values, $businessRange, static::$timezone);
    }


    public function filterLink($row, $value)
    {
        $next = (new DateDepth())->next($this->field, $this->getCarbonDate(parent::getValue($row)), new Filters());
        if (!$next) { return $this->getValue($row); }
        $selectValue = $this->field . ":" . "day";
        return "<a onclick='dateInDepth(\"{$this->getFilterField()}\", \"{$next['select']}\", \"{$next['start']}\", \"{$next['end']}\")' class='pointer'>{$value}</a>";
    }


    //===============================================================
    // DATE RANGES
    //===============================================================
    public static function businessRange(string $start = null, string $end = null) : array {
        $start       = ! $start ? Carbon::now() : Carbon::parse($start);
        $end         =  ! $end  ? $start->copy() : Carbon::parse($end);
        $openingTime = static::$openingTime;
        return [
            static::getParsedUTCDate($start->toDateString() . ' ' . $openingTime),
            static::getParsedUTCDate($end->toDateString() . ' ' . $openingTime)->addDay()
        ];

        return Carbon::parse($date, static::timezone)->utc();
    }

    public static function offsetHours() : int
    {
        return Carbon::now(static::$timezone)->offsetHours;
    }

    static function getParsedUTCDate($date)
    {
        return Carbon::parse($date, static::$timezone)->utc();
    }

    public static function getUtcOpeningTime() : string
    {
        return Carbon::parse(static::$openingTime, static::$timezone)->timezone('utc')->format('h:i:s');
    }
}