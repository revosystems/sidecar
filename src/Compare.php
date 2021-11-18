<?php

namespace Revo\Sidecar;

use Illuminate\Support\Str;
use Revo\Sidecar\ExportFields\Date;
use Revo\Sidecar\ExportFields\ExportField;

class Compare
{
    protected Report $period1;
    protected Report $period2;
    protected $period1Results;
    protected $period2Results;

    protected ExportField $groupByField;
    protected string $metric;

    public $results;
    public $labels;

    public $start;
    public $nd;

    public $colors = ["#E75129", "#B4B473", "#E2AA76", "#E9D25F", "#69625F", "#A39F9E", "#EB4E5D", "#F1EAC1", "#1A2E39", "#CDB194"];

    public function __construct($report)
    {
        $this->period1 = $report;
        $this->period2 = clone $report;
        $this->findGroupByField();
        $this->setPeriod2Dates();
    }

    public function setPeriod2Dates()
    {
        $this->period2->filters = clone $this->period1->filters;
        $this->start = request('compare')['start'] ?? "";
        $this->end = request('compare')['end'] ?? "";

        $this->period2->filters->dates[$this->getDateKey()] = [
            'start' => $this->start,
            'end'   => $this->end
        ];
    }

    public function calculate() : self
    {
        if (!$this->isComparing()) { return $this; }

        $this->period1Results = $this->period1->paginate()->mapWithKeys(function($row){
           return [$this->groupByField->getValue($row) => $row->{$this->metric}];
        });
        $this->period2Results = $this->period2->paginate()->mapWithKeys(function($row){
            return [$this->groupByField->getValue($row) => $row->{$this->metric}];
        });

        $this->labels = $this->period1Results->keys()->merge($this->period2Results->keys())->unique();
        $this->results = collect([$this->period1Results, $this->period2Results])->map(function ($results, $index) {
           return ['title' => 'Period ' . ($index + 1), 'values' => $this->labels->mapWithKeys(function($groupId) use($results){
              return [$groupId => $results[$groupId] ?? 0];
           })];
        })->toArray();
        $this->labels = $this->labels->values();
        return $this;
    }

    public function findGroupByField()
    {
        $groupBy = $this->period1->filters->groupBy->groupings->keys()->first();
        $this->groupByField = $this->period1->fields()->first(function (ExportField $field) use($groupBy) {
            return $field->getFilterField() == $groupBy;
        });
        $this->metric = $this->groupByField->aggregatedField ?? 'total';
    }

    public function getDateKey()
    {
        return $this->period1->fields()->first(function (ExportField $field) {
            return $field instanceof Date;
        })->field;
    }

    public function isComparing() : bool
    {
        return request('shouldCompare') == 'true';
    }
}