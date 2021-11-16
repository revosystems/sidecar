<?php

namespace Revo\Sidecar\Filters;

use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Report;

class Graph
{
    protected Report $report;
    protected ?ExportField $field;

    public $results;
    public $labels;
    public $values;

    public $colors = ["#E75129", "#B4B473", "#E2AA76", "#E9D25F", "#69625F", "#A39F9E"];

    public function __construct(Report $report, $results = null)
    {
        $this->report = $report;
        $this->results = $results;
        $this->findField();
    }

    public function doesApply(){
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
        $dimension = $this->dimension();
        $this->field = $this->report->fields()->first(function (ExportField $field) use($dimension) {
            return $field->getFilterField() == $dimension;
        });
    }

    public function calculate() : self {
        if (!$this->doesApply()) { return $this; }
        if (!$this->results) {
            $this->sresult = $this->report->paginate(25);
        }
        $this->labels = $this->results->map(function($row){
            return $this->field->getValue($row);
        });
        /*$this->values2 = $this->results->groupBy($this->dimension())->mapWithKeys(function($group, $key){
            return [$key => $group->pluck($this->field->groupableAggregatedField)];
        })->all();*/
//        dd($this->values2);
        $this->values = [
            $this->results->pluck($this->field->groupableAggregatedField),
            $this->results->pluck($this->field->groupableAggregatedField)
        ];
        return $this;
    }

    private function dimension(){
        return $this->report->filters->groupBy->groupings->keys()->first();
    }
}