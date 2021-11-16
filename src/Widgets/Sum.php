<?php

namespace Revo\Sidecar\Widgets;

class Sum extends Widget
{
    public $decimals = 2;

    public function decimals($decimals) : self {
        $this->decimals = $decimals;
        return $this;
    }
    public function getSelectField($groupBy = null)
    {
        return "sum({$this->field}) as {$this->field}";
    }

    public function getValue($row): string
    {
        return number_format(data_get($row, $this->field), $this->decimals);
    }
}