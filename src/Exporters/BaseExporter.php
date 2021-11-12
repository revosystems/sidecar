<?php

namespace Revo\Sidecar\Exporters;

use Revo\Sidecar\Report;

class BaseExporter
{

    protected $data;
    protected $fields;

    public function __construct($data, Report $report)
    {
        $this->data = $data;
        $this->fields = $report->fields();
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