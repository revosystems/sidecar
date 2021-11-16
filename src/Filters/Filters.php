<?php

namespace Revo\Sidecar\Filters;

use App\Models\EloquentBuilder;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Revo\Sidecar\ExportFields\ExportField;

class Filters
{
    protected $requestFilters = [];
    protected $dates = [];
    public $groupBy;
    public $sort;

    public function __construct() {
        $this->requestFilters = request('filters');
        $this->dates          = request('dates');
        $this->groupBy        = new GroupBy(request('groupBy'));
        $this->sort           = new Sort(request('sort'), request('sort_order'));
    }

    public function apply($query, $fields) : EloquentBuilder {
        collect($this->requestFilters)->each(function($value, $key) use($query){
            $this->applyFilter($query, $key, $value);
        });
        collect($this->dates)->each(function($value, $key) use($query){
            $this->applyDateFilter($query, $key, $value);
        });
        $this->addJoins($query, $fields);
        $this->groupBy->group($query);
        $this->sort->sort($query);
        return $query;
    }

    public function isFilteringBy($key, $value = null) : bool
    {
        if ($value == null) {
            return array_key_exists($key, $this->requestFilters ?? []);
        }
        return in_array($value, $this->requestFilters[$key] ?? []);
    }

    private function applyFilter($query, $key, $value)
    {
        if (is_array($value)) {
            return $query->whereIn($key, $value);
        }
        $query->where($key, $value);
    }

    public function applyDateFilter($query, $key, $values)
    {
        if ($values['start'] == null && $values['end'] == null) { return $query; }
        if ($values['end'] == null) {
            return $query->where($key, '>', $values['start']);
        }
        if ($values['start'] == null) {
            return $query->where($key, '<', $values['end']);
        }
        return $query->whereBetween($key, [$values['start'], $values['end']]);
    }

    public function addJoins($query, $fields)
    {
        $fields->each(function(ExportField $field) use($query) {
            $field->addJoin($query, $this, $this->groupBy);
        });
    }
}