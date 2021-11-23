<?php

namespace Revo\Sidecar\Panels;

use Illuminate\Support\Str;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Report;

class Panel
{
    public Report $report;
    public string $title;
    public string $metric;

    public function __construct(string $title, $report, Filters $filters = null, $metric)
    {
        $this->title = $title;
        $this->report = $report;
        $this->report->filters = $filters;
        $this->metric = $metric;
    }

    public function render() : string
    {
        return view('sidecar::panels.loading', ["panel" => $this])->render();
    }

    public function renderCalculated() : string
    {
        $results = $this->report->get();
        $metric = $this->findMetricField();
        return view('sidecar::panels.bigNumber', [
            "panel" => $this,
            "last"   => number_format($results->last()->{$this->metric}, 2),
            "values" => $this->getValues($results),
            "labels" => $this->getLabels($results)
        ])->render();
    }

    public function slug() : string
    {
        return Str::slug($this->title);
    }

    public function getLabels($results)
    {
        $dimension = $this->findDimensionField();
        return $results->map(function($value) use($dimension) {
            return $dimension->getValue($value);
        });
    }

    public function getValues($results)
    {
        $metric = $this->findMetricField();
        return $results->map(function($value) use($metric) {
            return optional($metric)->getValue($value) ?? $value->{$this->metric};
        });
    }

    public function findMetricField() : ?ExportField
    {
        return $this->report->fields()->first(function(ExportField $field){
            return $field->field == $this->metric;
        });
    }

    public function findDimensionField() : ExportField
    {
        return $this->report->fields()->first(function(ExportField $field){
            return $field->field == "opened";
        });
    }
}