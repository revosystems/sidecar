<?php

namespace Revo\Sidecar\ExportFields;

use Revo\Sidecar\Formatters\DecimalFormatter;

class Decimal extends Number
{
    protected int $decimals = 2;

    public function toHtml($row): string
    {
        return DecimalFormatter::toHtml($this->getValue($row), $this->decimals);
    }

    public function toCsv($row)
    {
        return DecimalFormatter::toCsv($this->getValue($row), $this->decimals);
    }

    public function decimals(int $decimals): self
    {
        $this->decimals = $decimals;
        return $this;
    }

    public function isNumeric(): bool
    {
        return true;
    }
}