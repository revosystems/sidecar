<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Revo\Sidecar\Enums\ComputedDisplayFormat;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;

class Computed extends ExportField
{
    protected ComputedDisplayFormat $displayFormat;

    public function getSelectField(?GroupBy $groupBy = null): ?string
    {
        if ($groupBy && $groupBy->isGrouping() && $this->onGroupingBy) {
            return "({$this->onGroupingBy}) as $this->title";
        }
        if ($groupBy && $groupBy->isGrouping()) {
            return null;
        }
        return "({$this->field}) as $this->title";
    }

    public function getValue($row)
    {
        return data_get($row, $this->title);
    }

    public function toHtml($row): string
    {
        return $this->displayFormat->toHtml($this->getValue($row));
    }

    public function toCsv($row)
    {
        return $this->displayFormat->toCsv($this->getValue($row));
    }

    public function displayFormat(ComputedDisplayFormat $format) : self
    {
        $this->displayFormat = $format;
        return $this;
    }

    public function getFilterField() : string
    {
        return $this->title; // Computed fields has to use their name to filter or sort
    }

    public function applySort(Filters $filters, Builder $query)
    {
        $filters->sort->sort($query, $filters->sort->field); // Computed fields are not database fields, so we don't need to add the database full
    }

    public function getTitle(): string
    {
        return __(config('thrust.translationsPrefix') . $this->title);
    }
}
