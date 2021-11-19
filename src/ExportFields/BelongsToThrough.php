<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use Revo\Sidecar\Filters\GroupBy;
use Revo\Sidecar\Filters\Filters;

class BelongsToThrough extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];
    protected $pivot = null;
    protected bool $useLeftJoin = false;

    public function getValue($row)
    {
        return data_get($row, "{$this->pivot}.{$this->field}.{$this->relationShipField}");
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
        $foreingKey = $this->pivot()->getRelated()->{$this->field}()->getForeignKeyName();
        if ($groupBy && !$groupBy->isGroupingBy($foreingKey)) { return $pivotSelectField; }
        return [
            $pivotSelectField,
            config('database.connections.mysql.prefix').$this->pivot()->getRelated()->getTable() .'.'.$foreingKey,
        ];
    }

    public function getFilterField() : string {
        $foreingKey = $this->pivot()->getRelated()->{$this->field}()->getForeignKeyName();
        return $foreingKey;
    }

    public function withLeftJoin(bool $leftJoin) : self {
        $this->leftJoin = $leftJoin;
        return $this;
    }

    protected function pivot(){
        return (new $this->model)->{$this->pivot}();
    }

    protected function relation(){
        return $this->pivot()->{$this->field}();
    }

    public function filterOptions() : array
    {
        return $this->pivot()->getRelated()->{$this->field}()->getRelated()->with($this->relationShipWith)->get()->pluck($this->relationShipField, 'id')->all();
    }

    public function addJoin(EloquentBuilder $query, Filters $filters, GroupBy $groupBy) : EloquentBuilder
    {
        if (!$filters->isFilteringBy($this->getFilterField()) && !$groupBy->isGroupingBy($this->getFilterField())) {
            return $query;
        }
        $pivot = $this->pivot()->getRelated()->getTable();
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
        $pivot = $this->pivot()->getRelated()->getTable();
        $filters->groupBy->groupBy($query, config('database.connections.mysql.prefix').$pivot.'.'.$key, $type);
    }


    public function applyFilter(Filters $filters, EloquentBuilder $query, $key, $values): EloquentBuilder
    {
        $pivot = $this->pivot()->getRelated()->getTable();
        return $filters->applyFilter($query, $pivot.'.'.$key, $values);
    }


}
