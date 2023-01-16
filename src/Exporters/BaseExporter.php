<?php

namespace Revo\Sidecar\Exporters;

use Revo\Sidecar\ExportFields\BelongsToThrough;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Report;

class BaseExporter
{

    protected Report $report;
    protected $data;

    public function __construct($data, Report $report)
    {
        $this->report = $report;
        $this->data = $data;
    }

    public function getFields()
    {
        return $this->report->fields()
            ->filter(fn (ExportField $field) => $field->shouldBeExported($this->report->filters));
    }

    public function getExportableFields()
    {
        return $this->report->exportableFields()
            ->filter(fn (ExportField $field) => $field->shouldBeExported($this->report->filters));
    }

    public function forEachRecord($callback)
    {
        $this->data->each(function($row) use($callback) {
            return $callback($row);
        });
    }
}
