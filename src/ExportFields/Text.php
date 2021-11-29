<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use Revo\Sidecar\Filters\Filters;

class Text extends ExportField
{
    public function applyFilter(Filters $filters, EloquentBuilder $query, $key, $values): EloquentBuilder
    {
        return parent::applySearch($filters, $query, $key, $values);
    }

    public function getFilterId($row)
    {
        return $this->getValue($row);
    }
}