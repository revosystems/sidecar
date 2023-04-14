<?php

namespace Revo\Sidecar\ExportFields;

use Revo\Sidecar\Formatters\CurrencyFormatter;

class Currency extends Number
{
    public bool $fromInteger = false;

    public function fromInteger(): self
    {
        $this->fromInteger = true;
        return $this;
    }

    public function getValue($row)
    {
        return $this->fromInteger
            ? parent::getValue($row) / 100
            : parent::getValue($row);
    }

    public function toHtml($row): string
    {
        return CurrencyFormatter::toHtml($this->getValue($row));
    }

    public function toCsv($row)
    {
        return $this->fromInteger
            ? $this->getValue($row)
            : CurrencyFormatter::toCsv($this->getValue($row));
    }

    public function mapValue(mixed $value): mixed
    {
        return $this->fromInteger
            ? parent::mapValue($value) / 100
            : parent::mapValue($value);
    }
}
