<?php

namespace Revo\Sidecar\Panels;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Report;
use Revo\Sidecar\Sidecar;

enum PanelType : string {
    case trend = 'trend';
    case list = 'list';
    case bar = 'bar';
    case pie = 'pie';
}

abstract class Panel extends Report
{
    public PanelType $type = PanelType::trend;

    public function __construct(string $title, Filters $filters = null)
    {
        $this->title = $title;
        $this->filters = $filters;
    }

    protected function getFields(): array
    {
        return [
            $this->dimensionField()->groupable(),
            $this->metricField()->sortable()
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
        if (app()->environment('local')) {
            Cache::forget($this->cacheKey());
        }
        return Cache::remember($this->cacheKey(), now()->endOfDay(), function(){
            $results = $this->get();
            $metric = $this->metricField();
            //dd($this->getValues($results), $this->getLabels($results));

            return view("sidecar::panels.{$this->type->value}", [
                "panel"  => $this,
                "last"   => $metric->toHtml($results->last()),
                "lastLabel" => $this->getLabels($results)->last(),
                "values" => $this->getValues($results),
                "labels" => $this->getLabels($results)
            ])->render();
        });
    }

    public function cacheKey(): string
    {
        if (Sidecar::$usesMultitenant) {
            return auth()->user()->id . '.panel.' . $this->slug();
        }
        return $this->slug();
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
        $dimension = $this->dimensionField();
        return $results->map(function($value) use($dimension) {
            return optional($dimension)->getValue($value) ?? "--";
        });
    }

    public function getFullReportLink() : ?string
    {
        return null;
    }

}