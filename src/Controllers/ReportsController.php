<?php

namespace Revo\Sidecar\Controllers;

use Revo\Sidecar\Compare;
use Revo\Sidecar\Exporters\HtmlExporter;
use Revo\Sidecar\Facades\Sidecar;
use Revo\Sidecar\Filters\Graph;

class ReportsController
{
    public function index($model)
    {
        $report = Sidecar::make(ucFirst($model) . "Report");
        $result = $report->paginate();

        return view("sidecar::index", [
            "model"              => $model,
            "report"             => $report,
            "exporter"           => new HtmlExporter($result, $report),
            "graph"              => (new Graph($report, $result))->calculate(),
            "compare"            => (new Compare($report))->calculate()
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
}