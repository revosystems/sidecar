<?php

namespace Revo\Sidecar\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Revo\Sidecar\Enums\DateRange;
use Revo\Sidecar\ExportFields\Date;
use Revo\Sidecar\ExportFields\ExportField;

class Filters
{
    public $requestFilters = [];
    public $dates = [];
    public $groupBy;
    public $sort;
    public $aggregateField = null;
    public $limit = null;

    public function __construct() {
        $this->requestFilters = $this->cleanupFilters(request('filters'));
        $this->dates          = request('dates');
        $this->groupBy        = new GroupBy(request('groupBy'));
        $this->sort           = new Sort(request('sort'), request('sort_order'));
        $this->aggregateField = request('aggregateField');
    }

    //======================================================================
    // SETUP HELPERS
    //======================================================================
    public function groupingBy($groupings) : self
    {
        $this->groupBy->groupings = collect($groupings);
        return $this;
    }

    public function forDates(string $key, Carbon $start, ?Carbon $end = null) : self {
        $this->dates[$key]['start'] = $start->toDateString();
        $this->dates[$key]['end'] = ($end ?? $start)->toDateString();
        return $this;
    }

    public function forPeriod(string $key, string $range) : self
    {
        $range = DateRange::from($range);
        $period = $range->period();
        $this->dates[$key]['period'] = $range->value;
        $this->dates[$key]['start'] = $period->getStartDate()->toDateString();
        $this->dates[$key]['end'] = $period->getEndDate()->toDateString();
        return $this;
    }

    public function sortBy($key, $order = 'DESC') : self {
        $this->sort->field = $key;
        $this->sort->order = $order;
        return $this;
    }

    public function aggregateWith($aggregateField)
    {
        $this->aggregateField = $aggregateField ;
        return $this;
    }

    public function limit($limit) : self {
        $this->limit = $limit;
        return $this;
    }

    //======================================================================
    // LOGIC
    //======================================================================
    public function apply($query, $fields) : Builder {
        $this->addFilters($query, $fields)
             ->addJoins($query, $fields)
             ->addGroups($query, $fields)
             ->addSorts($query, $fields)
             ->addLimit($query);
        return $query;
    }

    public function filtersFor($field)
    {
        return collect($this->requestFilters[$field] ?? []);
    }

    public function dateFiltersFor($field)
    {
        return collect($this->dates[$field] ?? []);
    }

    public function isFilteringBy($key, $value = null) : bool
    {
        if ($value === null) {
            return array_key_exists($key, $this->requestFilters ?? []) ||
                (array_key_exists($key, $this->dates) && isset($this->dates[$key]['start_time'])) ||
                (array_key_exists($key, $this->dates) && isset($this->dates[$key]['end_time']));
        }
        return in_array($value, $this->requestFilters[$key] ?? []);
    }

    public function addFilters($query, $fields) : self
    {
        $this->fillDatesFieldsWithDefaultDatesWhenEmpty($fields);
    
        collect($this->dates)->each(function($value, $key) {
            $this->setEndTimeIfMissing($value, $key);
            $this->setEndIfMissing($value, $key);
            $this->setStartTimeIfMissing($value, $key);
            $this->setStartIfMissing($value, $key);
        });

        $this->applyFilters($query, $fields);
        return $this;
    }
    
    protected function setEndTimeIfMissing(&$value, $key)
    {
        if (isset($value['start_time']) && !isset($value['end_time'])) {
            $this->dates[$key]['end_time'] = '23:59';
        }
    }
    
    protected function setEndIfMissing(&$value, $key)
    {
        if (!isset($value['end']) && isset($value['start'])) {
            $this->dates[$key]['end'] = $value['start'];
        }
    }
    
    protected function setStartTimeIfMissing(&$value, $key)
    {

        if (isset($value['end_time']) && !isset($value['start_time'])) {
            $this->dates[$key]['start_time'] = '00:00';
        }
    }
    
    protected function setStartIfMissing(&$value, $key)
    {
        if (!isset($value['start']) && isset($value['end'])) {
            $this->dates[$key]['start'] = $value['end'];
        }
    }
    protected function applyFilters($query, $fields)
    {
        collect($this->requestFilters)->each(function($value, $key) use($query, $fields) {
            optional($this->fieldFor($fields, $key))->applyFilter($this, $query, $key, $value);
        });
        
        collect($this->dates)->each(function($value, $key) use($query, $fields) {
            optional($this->fieldFor($fields, $key))->applyFilter($this, $query, $key, $value);
        });
    }

    public function addGroups($query, $fields) : self
    {
        collect($this->groupBy->groupings)->each(function($type, $key) use($query, $fields){
            optional($this->fieldFor($fields, $key))->applyGroupBy($this, $query, $key, $type);
        });
        return $this;
    }

    public function addSorts($query, $fields) : self
    {
        optional($this->fieldFor($fields, $this->sort->field))->applySort($this, $query);
        return $this;
    }

    public function addLimit($query) : self
    {
        if ($this->limit) {
            $query->limit($this->limit);
        }
        return $this;
    }

    public function fieldFor($fields, $filterField) :?ExportField {
        return $fields->first(function(ExportField $field) use($filterField){
            return ($field->filterable || $field->groupable || $field->sortable) && $field->getFilterField() == $filterField;
        });
    }

    public function applyFilter($query, $key, $value)
    {
        if (is_array($value)) {
            return $query->whereIn($key, $value);
        }
        return $query->where($key, $value);
    }

    public function applySearch($query, $key, $values)
    {
        return $query->where(function ($query) use($values, $key) {
            collect($values)->each(function($value) use($query, $key) {
                $query->orWhere($key, 'like', "%{$value}%");
            });
        });
    }

    private function getDefaultDates() : array {
        if (session('sidecar.date') != null) {
            return session('sidecar.date');
        }
        return [
            'start' => Carbon::today()->subDays(7)->toDateString(),
            'end' => Carbon::today()->toDateString()
        ];
    }

    public function applyDateFilter($query, $key, $values)
    {
        // As we always want to limit dates, we fill them with one week default value
        /*
        if ($values['start'] == null && $values['end'] == null) { return $query; }
        if ($values['end'] == null) {
            return $query->where($key, '>', $values['start']);
        }
        if ($values['start'] == null) {
            return $query->where($key, '<', $values['end']);
        }*/
        return $query->whereBetween($key, [$values[0], $values[1]]);
    }

    public function applyTimeFilter($query, $key, $timeValues, $dateValues, $timezone)
    {
        if (!Str::contains($key, config('database.connections.mysql.prefix'))){
            $key = config('database.connections.mysql.prefix') . $key;
        }

        $startDate = $dateValues[0];
        $endDate = $dateValues[1];
        $startTime = $timeValues['start_time'] ?? null;
        $endTime = $timeValues['end_time'] ?? null;
      

        if ($startTime && $endTime) {
            if ($startTime <= $endTime) {
                $query->where(function ($subQuery) use ($key, $startTime, $endTime, $timezone) {
                    $subQuery->where(DB::raw("CONVERT_TZ({$key}, 'UTC', '{$timezone}')"), ">=", DB::raw("CONCAT(DATE({$key}), ' {$startTime}')"))
                            ->where(DB::raw("CONVERT_TZ({$key}, 'UTC', '{$timezone}')"), "<", DB::raw("CONCAT(DATE({$key}), ' {$endTime}')"));
                });
            } else {
                $query->where(function ($subQuery) use ($key, $startTime, $endTime, $timezone) {
                    $subQuery->where(function ($timeQuery) use ($key, $startTime, $timezone) {
                        $timeQuery->where(DB::raw("CONVERT_TZ({$key}, 'UTC', '{$timezone}')"), ">=", DB::raw("CONCAT(DATE({$key}), ' {$startTime}')"));
                    })->orWhere(function ($timeQuery) use ($key, $endTime, $timezone) {
                        $timeQuery->where(DB::raw("CONVERT_TZ({$key}, 'UTC', '{$timezone}')"), "<", DB::raw("CONCAT(DATE({$key}), ' {$endTime}')"));
                    });
                });
            }
        }
        $query->whereRaw("DATE({$key}) BETWEEN '{$startDate}' AND '{$endDate}'");

        return $query;
    }

    public function addJoins($query, $fields) : self
    {
        $fields->each(function(ExportField $field) use($query) {
            $field->addJoin($query, $this);
        });
        return $this;
    }

    private function fillDatesFieldsWithDefaultDatesWhenEmpty($fields): void
    {
        $fields->filter(function (ExportField $field) {
            return $field instanceof Date && !isset($this->dates[$field->getFilterField()]);
        })->each(function(ExportField $field){
            $this->dates[$field->getFilterField()] = $this->getDefaultDates();
        });
    }

    public function dateFilterTitleFor(ExportField $field)
    {
        if ($period = $this->datePeriodFilterFor($field)) {
            if ($period != 'custom') {
                return __(config('sidecar.translationsPrefix') . $period);
            }
        }
        return Carbon::parse($this->dateFilterStartFor($field))->isoFormat('D MMM YY')  ." - " .
               Carbon::parse($this->dateFilterEndFor($field))->isoFormat('D MMM YY');
    }

    public function datePeriodFilterFor(ExportField $field) : ?string
    {
        return $this->dates[$field->getFilterField()]['period'] ?? null;
    }

    public function dateFilterStartFor(ExportField $field) {
        return $this->dates[$field->getFilterField()]['start'] ?? "";
    }

    public function dateFilterEndFor(ExportField $field) {
        return $this->dates[$field->getFilterField()]['end'] ?? "";
    }

    public function getQueryString() : string
    {
        $dates = $this->getDatesQuery()->implode("&");
        $filters = $this->getFiltersQuery()->implode("&");
        $groupings = $this->getGroupsQuery()->implode("&");

        $aggregateField = $this->aggregateField ? "aggregateField={$this->aggregateField}" : null;
        $sort = $this->sort->field ? "sort={$this->sort->field}&sort_order={$this->sort->order}" : null;
        return collect([$dates, $filters, $groupings, $sort, $aggregateField])->filter()->implode("&");
    }

    private function cleanupFilters(?array $filters): ?array
    {
        if (!$filters) { return $filters; }
        return collect($filters)->reject(function ($value, $key) {
            return isset($value['operand'])
                ? ($value['value'] ?? null) == null
                : ($value[0] ?? null) == null;
        })->all();
    }

    public function getDatesQuery(): Collection
    {
        return collect($this->dates)->map(function ($values, $key) {
            return collect([
                isset($values['period']) ? "dates[$key][period]={$values['period']}" : null,
                "dates[$key][start]={$values['start']}",
                "dates[$key][end]={$values['end']}",
            ])->filter()->implode("&");
        });
    }

    public function getFiltersQuery(): Collection
    {
        return collect($this->requestFilters)->map(function (mixed $value, string $key) {
            if (is_array($value)) {
                $value = implode(",", $value);
            }
            return "filters[$key]={$value}";
        });
    }

    public function getGroupsQuery(): Collection
    {
        return $this->groupBy->groupings->map(function ($type, $key) {
            return "groupBy[]={$key}:{$type}";
        });
    }

    /**
     * @param $field
     * @return string whereIn / whereNotIn
     */
    public function getOperandFor($field) : string {
        return $this->requestFilters[$field . '-operand'] ?? 'whereIn';
    }
}
