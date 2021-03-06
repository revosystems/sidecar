<?php

namespace Revo\Sidecar\Filters;

use Revo\Sidecar\ExportFields\Date;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Report;

class Graph
{
    protected Report $report;
    protected ?ExportField $dimensionField = null;
    protected ?ExportField $metricField = null;

    public $results;
    public $labels;
    public $values;

    public static $colors = ["#E75129", "#B4B473", "#E9D25F", "#77E2CD", "#A39F9E", "#EB4E5D", "#F1EAC1", "#C26BE0", "#69625F", "#E2828D", "#1A2E39", "#CDB194", "#E2AA76"];

    public function __construct(Report $report, $results = null)
    {
        $this->report = $report;
        $this->results = $results;
        $this->findDimensionField();
        $this->findMetricField();
    }

    public function doesApply(){
        return $this->report->filters->groupBy->groupings->count() > 0 && $this->report->filters->groupBy->groupings->count() < 3
            && $this->dimensionField != null;
    }

    public function getType() : string {
        if ($this->report->filters->groupBy->groupings->count() == 2) {   //two dimensions
            return 'bar';
        }
        return $this->dimensionField->groupableGraphType;
    }

    public function findDimensionField() {
        $dimension = $this->dimension();
        $this->dimensionField = $this->report->fields()->first(function (ExportField $field) use($dimension) {
            return $field->getFilterField() == $dimension && $field->groupableWithChart;
        });
    }

    public function findMetricField() {
        $metric = $this->metric();
        $this->metricField = $this->report->fields()->first(function (ExportField $field) use($metric) {
            return $field->getFilterField() == $metric && $field->groupable;
        });
    }

    public function findMetricFieldForOneGrouping(): ?ExportField
    {
        return $this->report->filters->fieldFor($this->report->fields(), $this->getAggregateField());
    }

    public function calculate() : self {
        if (!$this->doesApply()) { return $this; }
        if (!$this->results) {
            $this->sresult = $this->report->paginate(25);
        }
        if ($this->report->filters->groupBy->groupings->count() == 1) {
            return $this->calculateForOneGrouping();
        }
        if ($this->report->filters->groupBy->groupings->count() == 2) {
            return $this->calculateForTwoGroupings();
        }
        return $this;
    }

    public function calculateForOneGrouping()
    {
        $this->labels = $this->results->map(function($row){
            return $this->dimensionField->getValue($row);
        });
        $metricField = $this->findMetricFieldForOneGrouping();
        $metrics = $this->results->map(function($row) use($metricField){
            return $metricField->getValue($row);
        });

        $this->values = [
            [
                "title" => "",
                "values" => $metrics,
//                "values" => $this->results->pluck($this->getAggregateField()), //now we find the export field
            ]
        ];
        return $this;
    }

    public function calculateForTwoGroupings()
    {
        $dimension = $this->dimension();
        $metric = $this->metric();
        $this->labels = $this->results->mapWithKeys(function($row){
            return [(string)$row->{$this->dimensionField->getFilterField()} => $this->dimensionField->getValue($row)];
        })->unique();
        $metrics = $this->results->mapWithKeys(function($row) use($metric){
            return [(string)$row->{$metric} => $this->metricField->getValue($row)];
        });
        $a = $metrics->map(function($name, $metric) use($dimension) {
            return ["title" => $name, "values" => $this->labels->map(function($dimensionValue, $dimensionKey) use($dimension, $metric) {
                $result = $this->results->where($this->metric(), $metric)->first(function($row) use($dimensionValue) {
                    return $this->dimensionField->getValue($row) == $dimensionValue;
                });
                return $result->{$this->getAggregateField()} ?? 0;
//                return $this->results->where($dimension, $dimensionKey)->where($this->metric(), $metric)->first()->{$this->dimensionField->groupableAggregatedField} ?? 0;
            })->values()];
        });
        $this->labels = $this->labels->values();
        $this->values = $a->toArray();
//        dd($this->values);
        return $this;
    }

    /**
     * @return finds the dimension, first searching into dates
     */
    private function dimension() : ?string {
        $dimension = $this->report->filters->groupBy->groupings->only(["created_at", "updated_at", "date", "opened", "closed"])->keys()->first();
        if ($dimension) { return $dimension; }
        return $this->report->filters->groupBy->groupings->keys()->first();
    }

    private function metric() : ?string{
        return $this->report->filters->groupBy->groupings->except($this->dimension())->keys()->last();
    }

    public function getAggregateField(): string
    {
        return $this->report->filters->aggregateField ?? $this->dimensionField->groupableAggregatedField;
    }


}