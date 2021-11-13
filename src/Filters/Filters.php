<?php

namespace Revo\Sidecar\Filters;

class Filters
{
    protected $globalFilters  = [];
    protected $requestFilters = [];
    protected $customFilters  = [];

    public function __construct() {
        $this->requestFilters = request()->all();
    }

    public function apply($query){
        collect($this->requestFilters)->each(function($value, $key) use($query){
            $this->applyFilter($query, $key, $value);
        });
        return $query;
    }

    private function applyFilter($query, $key, $value)
    {
        if (is_array($value)) {
            return $query->whereIn($key, $value);
        }
        $query->where($key, $value);
    }

}