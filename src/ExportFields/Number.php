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

    public function applySort(Filters $filters, EloquentBuilder $query)
    {
        if (!$filters->groupBy->isGrouping()){
            return parent::applySort($filters, $query);
        }
        $filters->sort->sort($query, $filters->sort->field);
    }
}