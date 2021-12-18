<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Revo\Sidecar\Filters\Filters;

class Text extends ExportField
{
    public function applyFilter(Filters $filters, Builder $query, $key, $values): Builder
    {
        return parent::applySearch($filters, $query, $key, $values);
    }

    public function getFilterId($row)
    {
        return $this->getValue($row);
    }
}