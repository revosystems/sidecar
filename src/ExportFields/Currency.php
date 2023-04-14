<?php

namespace Revo\Sidecar\ExportFields;

class Currency extends Number
{
    public static \NumberFormatter $htmlFormatter;
    public static \NumberFormatter $csvFormatter;
    public static string $currency;
    public bool $fromInteger = false;

    public static function setFormatter($locale, $currency = 'EUR')
    {
        static::$htmlFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        static::$csvFormatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        static::$csvFormatter->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
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
        return static::$htmlFormatter->formatCurrency($this->getValue($row), static::$currency);
    }

    public function toCsv($row)
    {
        return $this->fromInteger
            ? $this->getValue($row)
            : static::$csvFormatter->format($this->getValue($row));
    }

    public function mapValue(mixed $value): mixed
    {
        return $this->fromInteger
            ? parent::mapValue($value) / 100
            : parent::mapValue($value);
    }
}
