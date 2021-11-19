<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;

class HasOne extends BelongsTo
{
    protected bool $defaultJoin = false;
    protected bool $useLeftJoin = false;

    public function withLeftJoin(bool $leftJoin) : self {
        $this->useLeftJoin = $leftJoin;
        return $this;
    }

    public function getSelectField(?GroupBy $groupBy = null): ?string
    {
        return null;
    }

    public function getFilterField(): string
    {
        return "no-filterable";
    }

    public function addJoin(EloquentBuilder $query, Filters $filters, GroupBy $groupBy): EloquentBuilder
    {
        if (!$this->defaultJoin) { return $query; }

        $main = (new $this->model)->getTable();
        $joinWith = $this->relation()->getRelated()->getTable();
        $foreignKey = $this->relation()->getForeignKeyName();
        if ($this->useLeftJoin) {
            return $query->leftJoin($joinWith, "{$main}.{$foreignKey}", "{$joinWith}.id");
        }
        return $query->join($joinWith, "{$main}.id", "{$joinWith}.id");
    }

    public function defaultJoin() : self
    {
        $this->defaultJoin = true;
        return $this;
    }

}