<?php

namespace Revo\Sidecar\Controllers;

use Revo\Sidecar\Compare;
use Revo\Sidecar\Exporters\CSVExporter;
use Revo\Sidecar\Exporters\HtmlExporter;
use Revo\Sidecar\Facades\Sidecar;
use Revo\Sidecar\Filters\Graph;

class ReportsExportController
{
    public function index($model)
    {
        $report = Sidecar::make(ucFirst($model) . "Report");
        $result = $report->get();
        return (new CSVExporter($result, $report))->download();
//        return view("sidecar::index", [
//            "model"              => $model,
//            "report"             => $report,
//            "exporter"           => new HtmlExporter($result, $report),
//            "graph"              => (new Graph($report, $result))->calculate(),
//            "compare"            => $compare
//        ]);
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