<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;

class HasOne extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];

    protected bool $defaultJoin = false;
    protected bool $useLeftJoin = false;

    public function getValue($row)
    {
        return data_get($row, "{$this->field}.{$this->relationShipField}");
    }

    public function relationShipDisplayField(string $relationShipDisplayField) : self {
        $this->relationShipField = $relationShipDisplayField;
        return $this;
    }

    public function getSelectField(?GroupBy $groupBy = null) : ?string
    {
        return null;
        /*$foreingKey = $this->relation()->getForeignKeyName();
        if ($groupBy && $groupBy->isGrouping() && !$groupBy->isGroupingBy($foreingKey)) { return null; }
        return $foreingKey;*/
    }

    public function relationShipWith(array $with) : self
    {
        $this->relationShipWith = $with;
        return $this;
    }

    public function filterOptions() : array
    {
        return $this->relation()->getRelated()->with($this->relationShipWith)->get()->pluck($this->relationShipField, 'id')->all();
    }

    public function searchableRoute() : string
    {
        $searchClass = get_class($this->relation()->getRelated());
        return route('sidecar.search.model', ["model" => $searchClass, "field" => $this->relationShipField]);
    }

    protected function relation(){
        return (new $this->model)->{$this->field}();
    }

    public function getEagerLoadingRelations()
    {
        return $this->field;
    }

    public function addJoin(EloquentBuilder $query, Filters $filters, GroupBy $groupBy): EloquentBuilder
    {
        if (!$this->defaultJoin) { return $query; }
        $joinTable = $this->relation()->getRelated()->getTable();
        $joinField = $this->relation()->getForeignKeyName();
        $main = (new $this->model)->getTable();

        if ($this->useLeftJoin) {
            return $query->leftJoin($joinTable, "{$joinTable}.{$joinField}", "{$main}.id");
        }
        return $query->join($joinTable, "{$joinTable}.{$joinField}", "{$main}.id");
    }

    public function withLeftJoin(bool $leftJoin) : self {
        $this->leftJoin = $leftJoin;
        return $this;
    }

    public function defaultJoin($defaultJoin = true) : self
    {
        $this->defaultJoin = $defaultJoin;
        return $this;
    }

}