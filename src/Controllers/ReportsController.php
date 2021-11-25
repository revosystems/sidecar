<?php

namespace Revo\Sidecar\Controllers;

use Illuminate\Support\Facades\Cookie;
use Revo\Sidecar\Compare;
use Revo\Sidecar\Exporters\HtmlExporter;
use Revo\Sidecar\Facades\Sidecar;
use Revo\Sidecar\Filters\Graph;
use Revo\Sidecar\CustomReports;

class ReportsController
{
    public function index($model)
    {
        $report = Sidecar::make(ucFirst($model) . "Report");
        $compare = (new Compare($report));
        if ($compare->isComparing()) {
            return view("sidecar::index", [
                "model"              => $model,
                "report"             => $report,
                "exporter"           => new HtmlExporter(null, $report),
                "graph"              => null,
                "compare"            => $compare->calculate()
            ]);
        }
        $result = $report->paginate();
        return view("sidecar::index", [
            "model"              => $model,
            "report"             => $report,
            "exporter"           => new HtmlExporter($result, $report),
            "graph"              => (new Graph($report, $result))->calculate(),
            "compare"            => $compare
        ]);
    }

    public function widgets($model){
        $report = Sidecar::make(ucFirst($model) . "Report");
        $widgetsResult = $report->widgetsQuery()->first();
        return view("sidecar::widgets", [
            "widgets"            => $report->getWidgets(),
            "widgetsResult"      => $widgetsResult,
        ]);
    }

    public function graph($model)
    {
        $report = Sidecar::make(ucFirst($model) . "Report");
        $graph = (new Graph($report))->calculate();

        return view("sidecar::graphs.graph", [
            'graph' => $graph
        ]);
    }


    public function store()
    {
        CustomReports::save(request('name'), request('url'));
        return back()->with(['message' => 'new report saved']);
    }
}