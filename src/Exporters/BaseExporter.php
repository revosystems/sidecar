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
        $this->fields = $report->fields();
        $filters = new Filters();
        if ($filters->groupBy){
            $this->fields = $this->fields->reject(function(ExportField $field) use($filters){
                return $field->hidden || ($field->onGroupingBy == null && $field->getFilterField($filters->groupBy) != $filters->groupBy);
            });
        }else{
            $this->fields = $this->fields->reject(function(ExportField $field) use($filters){
                return $field->hidden || $field->onlyWhenGrouping;
            });
        }
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