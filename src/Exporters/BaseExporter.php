<?php

namespace Revo\Sidecar\Exporters;

class BaseExporter
{

    protected $data;
    protected $fields;

    public function __construct($data, $fields)
    {
        $this->data = $data;
        $this->fields = $fields;
    }

    public function getFields()
    {
        return collect($this->fields);
    }

    public function forEachRecord($callback)
    {
        $this->data->each(function($row) use($callback) {
            return $callback($row);
        });
    }

}