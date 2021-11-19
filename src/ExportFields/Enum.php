<?php

namespace Revo\Sidecar\ExportFields;

class Enum extends ExportField
{
    protected array $options;

    public function options(array $options) : self
    {
        $this->options = $options;
        return $this;
    }

    public function getFilterOptions() : array
    {
        return $this->options;
    }

}