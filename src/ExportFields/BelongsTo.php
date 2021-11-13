<?php

namespace Revo\Sidecar\ExportFields;

class BelongsTo extends ExportField
{
    protected $relationShipField = 'name';

    public function getValue($row)
    {
        return data_get($row, "{$this->field}.{$this->relationShipField}");
    }

    public function getSelectField(?string $groupBy = null) : ?string
    {
        $foreingKey = $this->relation()->getForeignKeyName();
        if ($groupBy && $groupBy != $foreingKey) { return null; }
        return $foreingKey;
    }

    public function filterOptions() : array
    {
        return $this->relation()->getRelated()->all()->pluck($this->relationShipField, 'id')->all();
    }

    protected function relation(){
        return (new $this->model)->{$this->field}();
    }
}