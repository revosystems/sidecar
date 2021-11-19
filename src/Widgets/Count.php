<?php

namespace Revo\Sidecar\Widgets;

class Count extends Sum
{
    public $decimals = 0;

    public function getSelectField($groupBy = null)
    {
        return "count({$this->fullField()}) as {$this->field}";
    }
}