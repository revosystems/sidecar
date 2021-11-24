<?php

namespace Revo\Sidecar\Panels;

use Illuminate\Support\Str;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Report;

abstract class Panel extends Report
{
    public string $type = 'trend';
    public string $tooltip = 'Some nice tooltip';

    public function __construct(string $title, Filters $filters = null)
    {
        $this->title = $title;
        $this->filters = $filters;
    }

    protected function getFields(): array
    {
        return [
            $this->dimensionField(),
            $this->metricField()
        ];
    }

    abstract public function dimensionField() : ExportField;
    abstract public function metricField() : ExportField;

    public function render() : string
    {
        return view('sidecar::panels.loading', ["panel" => $this])->render();
    }

    public function renderCalculated() : string
    {
        $results = $this->get();
        $metric = $this->metricField();
//        dd($this->getValues($results), $this->getLabels($results));

        return view("sidecar::panels.{$this->type}", [
            "panel" => $this,
            "last"   => $metric->toHtml($results->last()),
            "values" => $this->getValues($results),
            "labels" => $this->getLabels($results)
        ])->render();
    }

    public function slug() : string
    {
        return Str::slug($this->title);
    }

    public function getValues($results)
    {
        $dimension = $this->metricField();
        return $results->map(function($value) use($dimension) {
            return $dimension->getValue($value);
        });
    }

    public function getLabels($results)
    {
        $metric = $this->dimensionField();
        return $results->map(function($value) use($metric) {
            return optional($metric)->getValue($value) ?? $value->{$this->metric};
        });
    }

}