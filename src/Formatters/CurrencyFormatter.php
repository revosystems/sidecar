<?php


namespace Revo\Sidecar\Formatters;

class CurrencyFormatter
{
    protected static \NumberFormatter $html;
    protected static \NumberFormatter $csv;
    protected static string $currency;
    public static function setFormatter($locale, $currency = 'EUR')
    {
        static::$html = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        static::$csv = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        static::$csv->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
        static::$currency = $currency;
    }

    public static function toHtml(mixed $value)
    {
        return CurrencyFormatter::$html->formatCurrency($value, CurrencyFormatter::$currency);
    }

    public static function toCsv(mixed $value)
    {
        return CurrencyFormatter::$csv->format($value);
    }
}