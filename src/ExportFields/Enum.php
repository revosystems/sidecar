<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Revo\Sidecar\Filters\Filters;

class Enum extends ExportField
{
    protected ?array $options = [];

    public function options(array $options) : self
    {
        $this->options = $options;
        return $this;
    }

    public function getValue($row)
    {
        $value = parent::getValue($row);
        return $value->value ?? $value;
    }

    public function toHtml($row): string
    {
        return $this->options[$this->getValue($row)] ?? '';
    }

    public function filterOptions() : array
    {
        return $this->options;
    }

    public function applyFilter(Filters $filters, Builder $query, $key, $values): Builder
    {
        if (count($values) == 0) { return $query; }
        $operand = $filters->getOperandFor($this->getFilterField());
        return $query->{$operand}($key, $values);

    }
}