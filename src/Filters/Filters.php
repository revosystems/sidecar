<?php

namespace Revo\Sidecar\Filters;

use App\Models\EloquentBuilder;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Revo\Sidecar\ExportFields\ExportField;

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

    public function apply($query, $fields) : EloquentBuilder {
        collect($this->requestFilters)->except(['groupBy', 'sort', 'sort_order', 'page'])->each(function($value, $key) use($query){
            $this->applyFilter($query, $key, $value);
        });
        $this->addJoins($query, $fields);
        (new GroupBy)->groupBy($query, $this->groupBy, $this->groupType);
        (new Sort)->sort($query, $this->requestFilters['sort'] ?? null, $this->requestFilters['sort_order'] ?? null);
        return $query;
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

    public function addJoins($query, $fields)
    {
        $fields->each(function(ExportField $field) use($query) {
            $field->addJoin($query, $this->requestFilters, $this->groupBy);
        });
    }
}