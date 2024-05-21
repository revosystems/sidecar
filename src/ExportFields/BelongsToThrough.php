<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Revo\Sidecar\Filters\GroupBy;
use Revo\Sidecar\Filters\Filters;

class BelongsToThrough extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];
    protected $pivot = null;
    protected bool $useLeftJoin = false;

    protected $filterOptions = null;

    public function getValue($row)
    {
        return data_get($row, "{$this->pivot}.{$this->field}.{$this->relationShipField}") ?? "--";
    }

    public function through(string $pivot) : self
    {
        $this->pivot = $pivot;
        return $this;
    }

    public function relationShipDisplayField(string $relationShipDisplayField) : self {
        $this->relationShipField = $relationShipDisplayField;
        return $this;
    }

    public function getSelectField(?GroupBy $groupBy = null)
    {
        $pivotSelectField = config('database.connections.mysql.prefix').(new $this->model)->getTable() . '.' . $this->pivot()->getForeignKeyName();
        if (!$groupBy) { return $pivotSelectField; };
        $foreingKey = $this->pivotForeingKey();
        if ($groupBy && !$groupBy->isGroupingBy($foreingKey)) { return $pivotSelectField; }
        return [
            $pivotSelectField,
            config('database.connections.mysql.prefix'). $this->getPivotTableAlias() .'.'.$foreingKey,
        ];
    }

    public function getFilterField() : string {
        return $this->pivotForeingKey();
    }

    public function withLeftJoin(bool $leftJoin) : self {
        $this->leftJoin = $leftJoin;
        return $this;
    }

    public function filterOptions(?Filters $filters = null) : array
    {
        if (!$this->filterOptions) {
            $query = $this->pivot()->getRelated()->{$this->field}()->getRelated()->with($this->relationShipWith);
            if ($this->filterSearchable) {
                $in = ($filters ?? new Filters())->filtersFor($this->getFilterField());
                if ($in->count() == 0) return [];
                $query->whereIn('id', $in);
            }
            $this->filterOptions = $query->get()->pluck($this->relationShipField, 'id')->all();
        }
        return $this->filterOptions;
    }

    public function getFilterId($row)
    {
        $foreignKey = $this->pivotForeingKey();
        return data_get($row, "{$this->pivot}.{$foreignKey}");
    }

    public function addJoin(Builder $query, Filters $filters) : Builder
    {
        if (!$filters->isFilteringBy($this->getFilterField()) && !$filters->groupBy->isGroupingBy($this->getFilterField()) && $filters->sort->field != $this->getFilterField()) {
            return $query;
        }
        $pivot = $this->getPivotTable();
        $main = (new $this->model)->getTable();
        $pivotAlias = $this->getPivotTableAlias();
        $foreingKey = ($this->pivot()->getForeignKeyName());
        if ($this->useLeftJoin) {
            return $query->leftJoin("{$pivot} as {$pivotAlias}", "{$pivotAlias}.id", "{$main}.{$foreingKey}");
        }
        return $query->join("{$pivot} as {$pivotAlias}", "{$pivotAlias}.id", "{$main}.{$foreingKey}");
    }

    public function getEagerLoadingRelations()
    {
        return "{$this->pivot}.{$this->field}";
    }

    public function applyGroupBy(Filters $filters, Builder $query, $key, $type)
    {
        $pivotAlias = $this->getPivotTableAlias();
        $filters->groupBy->groupBy($query, config('database.connections.mysql.prefix').$pivotAlias.'.'.$key, $type);
    }

    public function applyFilter(Filters $filters, Builder $query, $key, $values): Builder
    {
        if (count($values) == 0){ return $query; }
        $operand = $filters->getOperandFor($this->getFilterField());
        $pivotAlias = $this->getPivotTableAlias();
        $key = $pivotAlias.'.'.$key;
        if ($operand == 'whereNotIn'){
            return $query->whereNotIn($key, $values);
        }
        return $query->whereIn($key, $values);
    }

    public function applySort(Filters $filters, Builder $query)
    {
        $pivotAlias = $this->getPivotTableAlias();
        $filters->sort->sort($query, config('database.connections.mysql.prefix').$pivotAlias.'.'.$filters->sort->field);
    }

    //======================================================
    // RELATION METHODS
    //======================================================
    /*
     * Retuns the pivot relationship
     */
    protected function pivot(){
        return (new $this->model)->{$this->pivot}();
    }

    /*
     * Returns the final relationship
     */
    protected function relation(){
        return $this->pivot()->{$this->field}();
    }

    /**
     * @return string returns the foreing key in the pivot
     */
    private function pivotForeingKey() : string
    {
        return $this->pivot()->getRelated()->{$this->field}()->getForeignKeyName();
    }

    /** Returns the pivot table name */
    private function getPivotTable() : string
    {
        return $this->pivot()->getRelated()->getTable();
    }

    /** Returns the picot table name alias */
    private function getPivotTableAlias(): string
    {
        return $this->field . ucfirst($this->getPivotTable());
    }

    public function searchableRoute() : string
    {
        $searchClass = get_class($this->pivot()->getRelated()->{$this->field}()->getRelated());
        return route('sidecar.search.model', ["model" => $searchClass, "field" => $this->relationShipField]);
    }
}
