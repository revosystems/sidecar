<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;

class Computed extends ExportField
{
    protected ?string $displayFormat = null;

    public function getSelectField(?GroupBy $groupBy = null): ?string {
        if ($groupBy && $groupBy->isGrouping() && $this->onGroupingBy) {
            return "(". $this->onGroupingBy . ") as $this->title";
        }
        if ($groupBy && $groupBy->isGrouping()) {
            return null;
        }
        return "(". $this->field . ") as $this->title";
    }


    public function getValue($row) {
        $value = data_get($row, $this->title);
        if ($this->displayFormat == 'time' && is_numeric($value)){
            return gmdate("H:i:s", $value);
        }
        if ($this->displayFormat == 'currency' && isset(Decimal::$formatter)){
            return Decimal::$formatter->formatCurrency($value , 'EUR' );
        }
        if (!is_numeric($value)){
            return $value;
        }
        return number_format($value, 2);
    }

    public function displayFormat(string $format) : self {
        $this->displayFormat = $format;
        return $this;
    }

    public function getFilterField() : string {
        return $this->title; // Computed fields has to use their name to filter or sort
    }

    public function applySort(Filters $filters, Builder $query){
        $filters->sort->sort($query, $filters->sort->field); // Computed fields are not database fields, so we don't need to add the database full
    }

    public function getTitle(): string
    {
        return __(config('thrust.translationsPrefix').$this->title);
    }
}