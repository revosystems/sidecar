<?php

namespace Revo\Sidecar\ExportFields;

class Enum extends ExportField
{
    protected ?array $options = [];

    public function options(array $options) : self
    {
        $this->options = $options;
        return $this;
    }

    public function getValue($row)
    {
        $value = parent::getValue($row);
        return $value->value ?? $value;
    }

    public function toHtml($row): string
    {
        return $this->options[$this->getValue($row)] ?? '';
    }

    public function filterOptions() : array
    {
        return $this->options;
    }
}