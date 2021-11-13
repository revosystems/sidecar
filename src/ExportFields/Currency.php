<?php

namespace Revo\Sidecar\ExportFields;

class Currency extends ExportField
{
    static $formatter;
    static $currency = "€";

    public static function make($field, $title = null, $dependsOnField = null)
    {
        return parent::make($field, $title, $dependsOnField)->onGroupingBy('sum');
    }

    public static function setFormatter($locale, $currency = 'EUR')
    {
        static::$formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        static::$currency  = $currency;
    }

    public function getValue($row)
    {
        if (static::$formatter){
            return static::$formatter->formatCurrency(parent::getValue($row) , 'EUR' );
        }
        return number_format(parent::getValue($row), 2) . ' €';
    }

    public function isNumeric() : bool
    {
        return true;
    }

}