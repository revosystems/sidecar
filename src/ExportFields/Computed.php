<?php

namespace Revo\Sidecar\ExportFields;

class Computed extends Number
{
    protected bool $asCurrency = false;

    public function getSelectField(?string $groupBy = null): ?string {
        return "(". $this->field . ") as $this->title";
    }

    public static function make($field, $title = null, $dependsOnField = null)
    {
        return parent::make($field, $title, $dependsOnField)->onGroupingBy("show");
    }

    public function getValue($row) {
        $value = data_get($row, $this->title);
        if ($this->asCurrency && isset(Decimal::$formatter)){
            return Decimal::$formatter->formatCurrency($value , 'EUR' );
        }
        return number_format($value, 2);
    }

    public function asCurrency(bool $asCurrency = true) : self {
        $this->asCurrency = $asCurrency;
        return $this;
    }
}