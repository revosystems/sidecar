<?php

namespace Revo\Sidecar\ExportFields;

class Currency extends Number
{
    public static $formatter;
    public static $currency = "€";
    public $fromInteger = false;

    public static function setFormatter($locale, $currency = 'EUR')
    {
        static::$formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        static::$currency  = $currency;
    }

    public function fromInteger() : self
    {
        $this->fromInteger = true;
        return $this;
    }

    public function getValue($row)
    {
        if ($this->fromInteger) {
            return parent::getValue($row) / 100;
        }
        return parent::getValue($row);
    }

    public function toHtml($row): string
    {
        if (static::$formatter){
            return static::$formatter->formatCurrency($this->getValue($row) , 'EUR' );
        }
        return number_format($this->getValue($row), 2) . ' €';
    }

    public function toCsv($row)
    {
        if ($this->fromInteger) {
            return $this->getValue($row);
        }
        return number_format($this->getValue($row), 2);
    }

    public function mapValue(mixed $value): mixed
    {
        return $this->fromInteger
            ? parent::mapValue($value) / 100
            : parent::mapValue($value);
    }
}