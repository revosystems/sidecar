<?php

namespace Revo\Sidecar\Exporters;

class BaseExporter
{

    protected $data;
    protected $fields;

    public function __construct($data, ReportExporter $exporter)
    {
        $this->data = $data;
        $this->fields = $exporter->getFields();
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