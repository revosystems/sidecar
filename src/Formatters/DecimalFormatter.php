<?php


namespace Revo\Sidecar\Formatters;

class DecimalFormatter
{
    public static \NumberFormatter $html;
    public static \NumberFormatter $csv;

    public static function setFormatter(string $locale)
    {
        static::$html = \NumberFormatter::create($locale, \NumberFormatter::DECIMAL);
        static::$csv = \NumberFormatter::create($locale, \NumberFormatter::DECIMAL);
        static::$csv->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
    }

    public static function toHtml(mixed $value, int $decimals = 2)
    {
        $formatter = DecimalFormatter::$html;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, $decimals);
        return $formatter->format($value);
    }

    public static function toCsv(mixed $value, int $decimals = 2)
    {
        $formatter = DecimalFormatter::$csv;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, $decimals);
        return $formatter->format($value);
    }
}