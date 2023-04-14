<?php


namespace Revo\Sidecar\Enums;

use Revo\Sidecar\Formatters\CurrencyFormatter;
use Revo\Sidecar\Formatters\DecimalFormatter;

enum ComputedDisplayFormat
{
    case CURRENCY;
    case TIME;
    case INTEGER;
    case DECIMAL;

    public function toHtml($value)
    {
        return match ($this) {
            self::CURRENCY => CurrencyFormatter::toHtml($value),
            self::INTEGER => DecimalFormatter::toHtml($value, 0),
            self::DECIMAL => DecimalFormatter::toHtml($value),
            self::TIME => is_numeric($value) ? gmdate('H:i:s', $value) : $value,
        };
    }
    public function toCsv($value)
    {
        return match ($this) {
            self::CURRENCY => CurrencyFormatter::toCsv($value),
            self::INTEGER => DecimalFormatter::toHtml($value, 2),
            self::DECIMAL => DecimalFormatter::toCsv($value),
            self::TIME => is_numeric($value) ? gmdate('H:i:s', $value) : $value,
        };
    }
}