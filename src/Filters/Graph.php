<?php

namespace Revo\Sidecar\Filters;

use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Report;
use Revo\Sidecar\Filters\Filters;

class Graph
{
    protected Report $report;
    protected ?ExportField $field;

    public $results;
    public $filters;
    public $labels;
    public $values;

    public function __construct(Report $report, $results = null)
    {
        $this->report = $report;
        $this->filters = new Filters();
        $this->results = $results;
        $this->findField();
    }

    public function doesApply(Report $report){
        return $this->field != null && $this->field->groupableWithChart;
    }

    public function getTitle() : string {
        //return $this->report->getTitle();
        return "";
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
        if (!$this->results) {
            $this->sresult = $this->report->paginate(25);
        }
        $this->labels = $this->results->map(function($row){
            return $this->field->getValue($row);
        });
        $this->values = $this->results->pluck($this->field->groupableAggregatedField);
        return $this;
    }
}