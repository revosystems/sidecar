<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use phpDocumentor\Reflection\Types\Parent_;
use Revo\Sidecar\Filters\Filters;

class Number extends ExportField
{
    public static function make($field, $title = null, $dependsOnField = null)
    {
        return parent::make($field, $title, $dependsOnField)->onGroupingBy('sum');
    }

    public function isNumeric() : bool
    {
        return true;
    }

    public function filterOptions() : array
    {
        return [
            "=" => "equal",
            ">" => "greater",
            ">=" => "greaterOrEqual",
            "<" => "lower",
            "<=" => "lowerOrEqual",
        ];
    }

    public function applySort(Filters $filters, EloquentBuilder $query)
    {
        if (!$filters->groupBy->isGrouping()){
            return parent::applySort($filters, $query);
        }
        $filters->sort->sort($query, $filters->sort->field);
    }

    public function applyFilter(Filters $filters, EloquentBuilder $query, $key, $values): EloquentBuilder
    {
        if ($values['value'] == null) { return $query; }
        var_dump($values['operand'], $values['value']);
        return $query->where($this->databaseTable().'.'.$key, $values['operand'], $values['value']);
    }
}