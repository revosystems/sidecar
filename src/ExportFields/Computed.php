<?php

namespace Revo\Sidecar\ExportFields;

class Computed extends Number
{
    protected ?string $displayFormat = null;

    public function getSelectField(?string $groupBy = null): ?string {
        if ($groupBy && $this->onGroupingBy) {
            return "(". $this->onGroupingBy . ") as $this->title";
        }
        return "(". $this->field . ") as $this->title";
    }

    public static function make($field, $title = null, $dependsOnField = null)
    {
        return parent::make($field, $title, $dependsOnField)->onGroupingBy("show");
    }

    public function getValue($row) {
        $value = data_get($row, $this->title);
        if ($this->displayFormat == 'time' && is_numeric($value)){
            return gmdate("H:i:s", $value);
        }
        if ($this->displayFormat == 'currency' && isset(Decimal::$formatter)){
            return Decimal::$formatter->formatCurrency($value , 'EUR' );
        }
        if (!is_numeric($value)){
            return $value;
        }
        return number_format($value, 2);
    }

    public function displayFormat(string $format) : self {
        $this->displayFormat = $format;
        return $this;
    }
}