<?php


namespace Revo\Sidecar\Enums;

use Revo\Sidecar\ExportFields\Currency;
use Revo\Sidecar\ExportFields\Decimal;

enum ComputedDisplayFormat
{
    case CURRENCY;
    case TIME;
    case INTEGER;
    case DECIMAL;

    public function toHtml($value)
    {
        return match ($this) {
            self::CURRENCY => Currency::$htmlFormatter->formatCurrency($value, Currency::$currency),
            self::INTEGER => $this->getHtmlInteger($value),
            self::DECIMAL => $this->getHtmlDecimal($value),
            self::TIME => is_numeric($value) ? gmdate('H:i:s', $value) : $value,
        };
    }
    public function toCsv($value)
    {
        return match ($this) {
            self::CURRENCY => Currency::$csvFormatter->formatCurrency($value, Currency::$currency),
            self::INTEGER => $this->getCsvInteger($value),
            self::DECIMAL => $this->getCsvDecimal($value),
            self::TIME => is_numeric($value) ? gmdate('H:i:s', $value) : $value,
        };
    }

    protected function getHtmlInteger($value)
    {
        $formatter = Decimal::$htmlFormatter;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, 0);
        return $formatter->format($value);
    }

    protected function getCsvInteger($value)
    {
        $formatter = Decimal::$csvFormatter;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, 0);
        return $formatter->format($value);
    }

    protected function getHtmlDecimal($value)
    {
        $formatter = Decimal::$htmlFormatter;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, 2);
        return $formatter->format($value);
    }

    protected function getCsvDecimal($value)
    {
        $formatter = Decimal::$htmlFormatter;
        $formatter->setSymbol(\NumberFormatter::FRACTION_DIGITS, 2);
        return $formatter->format($value);
    }
}