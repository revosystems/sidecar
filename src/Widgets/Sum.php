<?php

namespace Revo\Sidecar\Widgets;

class Sum extends Widget
{

    public function getSelectField($groupBy = null)
    {
        return "sum({$this->field}) as {$this->field}";
    }

    public function getValue($row): string
    {
        return data_get($row, $this->field);
    }
}