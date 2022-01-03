<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use phpDocumentor\Reflection\Types\Parent_;
use Revo\Sidecar\Filters\Filters;

class Percentage extends Number
{
    public function toHtml($row): string
    {
        return parent::toHtml($row) . ' %';
    }
}