<?php

namespace Revo\Sidecar\Exporters;

use Revo\Sidecar\ExportFields\BelongsToThrough;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Report;

class BaseExporter
{

    protected $data;
    protected $fields;

    public function __construct($data, Report $report)
    {
        $this->data = $data;
        $this->fields = $report->fields()->filter(function(ExportField $field) use ($report) {
            return $field->shouldBeExported($report->filters);
        });
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