<?php

namespace Revo\Sidecar\Filters;

use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Report;
use Revo\Sidecar\Filters\Filters;

class Graph
{
    protected Report $report;
    protected ?ExportField $field;

    public $result;
    public $filters;
    public $labels;
    public $values;

    public function __construct(Report $report)
    {
        $this->report = $report;
        $this->filters = new Filters();
        $this->findField();
    }

    public function doesApply(Report $report){
        return $this->field != null && $this->field->groupableWithChart;
    }

    public function getTitle() : string {
        return $this->report->getTitle();
    }

    public function getType() : string {
        return $this->field->groupableGraphType;
    }

    public function findField() {
        $this->field = $this->report->fields()->first(function (ExportField $field){
            return $field->getFilterField() == $this->filters->groupBy;
        });
    }

    public function calculate() : self {
        $this->result = $this->report->paginate(25);
        $this->labels = $this->result->map(function($row){
            return $this->field->getValue($row);
        });
        $this->values = $this->result->pluck($this->field->groupableAggregatedField);
        return $this;
    }
}