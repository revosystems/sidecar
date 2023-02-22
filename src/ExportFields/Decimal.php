<?php

namespace Revo\Sidecar\ExportFields;

use phpDocumentor\Reflection\Types\Parent_;

class Decimal extends Number
{
    protected int $decimals = 2;

    public function getValue($row)
    {
        return number_format(parent::getValue($row), $this->decimals);
    }

    public function toCsv($row)
    {
        return number_format(parent::getValue($row), $this->decimals, thousands_separator: '');
    }

    public function decimals(int $decimals) : self
    {
        $this->decimals = $decimals;
        return $this;
    }

    public function isNumeric() : bool
    {
        return true;
    }
}