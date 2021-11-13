<?php

namespace Revo\Sidecar\ExportFields;

class Number extends ExportField
{
    public static function make($field, $title = null, $dependsOnField = null)
    {
        return parent::make($field, $title, $dependsOnField)->onGroupingBy('sum');
    }

    public function isNumeric() : bool
    {
        return true;
    }
}