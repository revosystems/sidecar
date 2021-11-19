<?php

namespace Revo\Sidecar\Filters;

use App\Models\EloquentBuilder;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Revo\Sidecar\ExportFields\Date;
use Revo\Sidecar\ExportFields\ExportField;

class Filters
{
    public $requestFilters = [];
    public $dates = [];
    public $groupBy;
    public $sort;

    public function __construct() {
        $this->requestFilters = request('filters');
        $this->dates          = request('dates');
        $this->groupBy        = new GroupBy(request('groupBy'));
        $this->sort           = new Sort(request('sort'), request('sort_order'));
    }

    public function apply($query, $fields) : EloquentBuilder {
        $this->addFilters($query, $fields)
             ->addJoins($query, $fields)
             ->addGroups($query, $fields)
             ->addSorts($query, $fields);
        return $query;
    }

    public function isFilteringBy($key, $value = null) : bool
    {
        if ($value == null) {
            return array_key_exists($key, $this->requestFilters ?? []);
        }
        return in_array($value, $this->requestFilters[$key] ?? []);
    }

    public function addFilters($query, $fields) : self
    {
        $this->fillDatesFieldsWithDefaultDatesWhenEmpty($fields);

        collect($this->requestFilters)->each(function($value, $key) use($query, $fields){
            optional($this->fieldFor($fields, $key))->applyFilter($this, $query, $key, $value);
        });
        collect($this->dates)->each(function($value, $key) use($query, $fields){
            optional($this->fieldFor($fields, $key))->applyFilter($this, $query, $key, $value);
        });
        return $this;
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

    protected function fieldFor($fields, $filterField) :?ExportField {
        return $fields->first(function(ExportField $field) use($filterField){
            return $field->getFilterField() == $filterField;
        });
    }

    public function applyFilter($query, $key, $value)
    {
        if (is_array($value)) {
            return $query->whereIn($key, $value);
        }
        $query->where($key, $value);
    }

    private function getDefaultDates() : array{
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
        return $query->whereBetween($key, [$values['start'], $values['end']]);
    }

    public function addJoins($query, $fields) : self
    {
        $fields->each(function(ExportField $field) use($query) {
            $field->addJoin($query, $this, $this->groupBy);
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

    public function dateFilterStartFor(ExportField $field) {
        return $this->dates[$field->getFilterField()]['start'] ?? "";
    }

    public function dateFilterEndFor(ExportField $field) {
        return $this->dates[$field->getFilterField()]['end'] ?? "";
    }
}