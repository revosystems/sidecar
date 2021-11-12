<?php

namespace Revo\Sidecar\ExportFields;

class Number extends ExportField
{
    public function isNumeric() : bool
    {
        return true;
    }
}