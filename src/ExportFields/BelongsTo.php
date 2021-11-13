<?php

namespace Revo\Sidecar\ExportFields;

class BelongsTo extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];

    public function getValue($row)
    {
        return data_get($row, "{$this->field}.{$this->relationShipField}");
    }

    public function relationShipDisplayField(string $relationShipDisplayField) : self {
        $this->relationShipField = $relationShipDisplayField;
        return $this;
    }

    public function getSelectField(?string $groupBy = null) : ?string
    {
        $foreingKey = $this->relation()->getForeignKeyName();
        if ($groupBy && $groupBy != $foreingKey) { return null; }
        return $foreingKey;
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

    protected function relation(){
        return (new $this->model)->{$this->field}();
    }
}