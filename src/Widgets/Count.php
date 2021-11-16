<?php

namespace Revo\Sidecar\Widgets;

class Count extends Widget
{

    public function getSelectField($groupBy = null)
    {
        return "count({$this->field}) as {$this->field}";
    }

    public function getValue($row): string
    {
        return data_get($row, $this->field);
    }
}