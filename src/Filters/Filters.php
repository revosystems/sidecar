<?php

namespace Revo\Sidecar\Filters;

use Carbon\CarbonPeriod;
use Carbon\Carbon;

class Filters
{
    protected $globalFilters  = [];
    protected $requestFilters = [];
    protected $customFilters  = [];

    public $groupBy = null;
    public $groupType = null;

    public function __construct() {
        $this->requestFilters = request()->all();
        if (request('groupBy')){
            list($this->groupBy, $this->groupType) = explode(":", request('groupBy'));
        }
    }

    public function apply($query){
        collect($this->requestFilters)->except('groupBy')->each(function($value, $key) use($query){
            $this->applyFilter($query, $key, $value);
        });
        return (new GroupBy)->groupBy($query, $this->groupBy, $this->groupType);
    }

    private function applyFilter($query, $key, $value)
    {
        if (is_array($value) && array_key_exists('start', $value)) {
            return $this->applyDateFilter($query, $key, $value);
        }
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
}