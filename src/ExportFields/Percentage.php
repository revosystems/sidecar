<?php

namespace Revo\Sidecar\ExportFields;

class Percentage extends Number
{
    public function toHtml($row): string
    {
        return parent::toHtml($row) . ' %';
    }
}