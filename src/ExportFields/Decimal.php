<?php

namespace Revo\Sidecar\ExportFields;

class Decimal extends Number
{
    public static \NumberFormatter $htmlFormatter;
    public static \NumberFormatter $csvFormatter;
    protected int $decimals = 2;

    public static function setFormatter(string $locale)
    {
        static::$htmlFormatter = \NumberFormatter::create($locale, \NumberFormatter::DECIMAL);
        static::$csvFormatter = \NumberFormatter::create($locale, \NumberFormatter::DECIMAL);
        static::$csvFormatter->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
    }

    public function toHtml($row): string
    {
        $formatter = static::$htmlFormatter;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, $this->decimals);
        return $formatter->format($this->getValue($row));
    }

    public function toCsv($row)
    {
        $formatter = static::$csvFormatter;
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