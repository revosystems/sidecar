<?php

namespace Revo\Sidecar\ExportFields;

class Time extends ExportField
{
    public function getValue($row)
    {
        $value = parent::getValue($row);
        return is_numeric($value) ? gmdate('H:i:s', $value) : $value;
    }
}