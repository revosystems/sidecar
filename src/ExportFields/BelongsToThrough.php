<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use App\Reports\V2\OrdersReport;
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
            config('database.connections.mysql.prefix'). $this->getPivotTable() .'.'.$foreingKey,
        ];
    }

    public function getFilterField() : string {
        return $this->pivotForeingKey();
    }

    public function withLeftJoin(bool $leftJoin) : self {
        $this->leftJoin = $leftJoin;
        return $this;
    }

    public function filterOptions() : array
    {
        if (!$this->filterOptions) {
            $this->filterOptions = $this->pivot()->getRelated()->{$this->field}()->getRelated()->with($this->relationShipWith)->get()->pluck($this->relationShipField, 'id')->all();
        }
        return $this->filterOptions;
    }

    public function getFilterId($row)
    {
        $foreignKey = $this->pivotForeingKey();
        return data_get($row, "{$this->pivot}.{$foreignKey}");
    }

    public function addJoin(EloquentBuilder $query, Filters $filters) : EloquentBuilder
    {
        if (!$filters->isFilteringBy($this->getFilterField()) && !$filters->groupBy->isGroupingBy($this->getFilterField()) && $filters->sort->field != $this->getFilterField()) {
            return $query;
        }
        $pivot = $this->getPivotTable();
        $main = (new $this->model)->getTable();
        $foreingKey = ($this->pivot()->getForeignKeyName());
        if ($this->useLeftJoin) {
            return $query->leftJoin($pivot, "{$pivot}.id", "{$main}.{$foreingKey}");
        }
        return $query->join($pivot, "{$pivot}.id", "{$main}.{$foreingKey}");
    }

    public function getEagerLoadingRelations()
    {
        return "{$this->pivot}.{$this->field}";
    }

    public function applyGroupBy(Filters $filters, EloquentBuilder $query, $key, $type)
    {
        $pivot = $this->getPivotTable();
        $filters->groupBy->groupBy($query, config('database.connections.mysql.prefix').$pivot.'.'.$key, $type);
    }

    public function applyFilter(Filters $filters, EloquentBuilder $query, $key, $values): EloquentBuilder
    {
        $pivot = $this->getPivotTable();
        return $filters->applyFilter($query, $pivot.'.'.$key, $values);
    }

    public function applySort(Filters $filters, EloquentBuilder $query)
    {
        $pivot = $this->getPivotTable();
        $filters->sort->sort($query, config('database.connections.mysql.prefix').$pivot.'.'.$filters->sort->field);
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
}
