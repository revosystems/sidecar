<?php

namespace Revo\Sidecar\ExportFields;

class Currency extends Number
{
    public bool $fromInteger = false;
    protected static \NumberFormatter $html;
    protected static \NumberFormatter $csv;
    protected static string $currency;
    public static function setFormatter($locale, $currency = 'EUR')
    {
        static::$html = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        static::$csv = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        //static::$csv->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);
        static::$csv->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
        static::$currency = $currency;
    }

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
        return static::$html->formatCurrency($this->getValue($row), static::$currency);
    }

    public function toCsv($row)
    {
        return $this->fromInteger
            ? $this->getValue($row)
            : static::$csv->format($this->getValue($row));
    }

    public function mapValue(mixed $value): mixed
    {
        return $this->fromInteger
            ? parent::mapValue($value) / 100
            : parent::mapValue($value);
    }
}
