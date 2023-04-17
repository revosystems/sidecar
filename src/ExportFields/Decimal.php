<?php

namespace Revo\Sidecar\ExportFields;

class Decimal extends Number
{
    protected int $decimals = 2;
    public static \NumberFormatter $html;
    public static \NumberFormatter $csv;

    public static function setFormatter(string $locale)
    {
        static::$html = \NumberFormatter::create($locale, \NumberFormatter::DECIMAL);
        static::$csv = \NumberFormatter::create($locale, \NumberFormatter::DECIMAL);
        static::$csv->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
    }

    public function toHtml($row): string
    {
        $formatter = static::$html;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, $this->decimals);
        return $formatter->format($this->getValue($row));

    }

    public function toCsv($row)
    {
        $formatter = static::$csv;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, $this->decimals);
        return $formatter->format($this->getValue($row));
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