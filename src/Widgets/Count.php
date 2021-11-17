<?php

namespace Revo\Sidecar\Widgets;

class Count extends Sum
{
    public $decimals = 0;

    public function getSelectField($groupBy = null)
    {
        return "count({$this->field}) as {$this->field}";
    }
}