<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use Revo\Sidecar\Filters\GroupBy;

class BelongsToThrough extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];
    protected $pivot = null;

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
        if (!$groupBy) { return null; };
        $foreingKey = $this->pivot()->getRelated()->{$this->field}()->getForeignKeyName();
        if ($groupBy && !$groupBy->isGroupingBy($foreingKey)) { return null; }
        return [
            config('database.connections.mysql.prefix').$this->pivot()->getRelated()->getTable() .'.'.$foreingKey,
            config('database.connections.mysql.prefix').(new $this->model)->getTable() . '.' . $this->pivot()->getForeignKeyName()
        ];
    }

    public function getFilterField() : string {
        $foreingKey = $this->pivot()->getRelated()->{$this->field}()->getForeignKeyName();
        return $foreingKey;
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

    public function addJoin(EloquentBuilder $query, $filters, GroupBy $groupBy) : EloquentBuilder
    {
        if (!array_key_exists($this->getFilterField(), $filters) && !$groupBy->isGroupingBy($this->getFilterField())) {
            return $query;
        }
        $pivot = $this->pivot()->getRelated()->getTable();
        $main = (new $this->model)->getTable();
        $foreingKey = ($this->pivot()->getForeignKeyName());
        return $query->join($pivot, "{$pivot}.id", "{$main}.{$foreingKey}");
    }

    public function getEagerLoadingRelations()
    {
        return "{$this->pivot}.{$this->field}";
    }
}
