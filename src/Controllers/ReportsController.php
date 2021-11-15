<?php

namespace Revo\Sidecar\Controllers;

use Revo\Sidecar\Exporters\HtmlExporter;
use Revo\Sidecar\Facades\Sidecar;

class ReportsController
{
    public function index($model)
    {
        $report = Sidecar::make(ucFirst($model) . "Report");
        $result = $report->paginate();
        return view("sidecar::index", [
            "report"             => $report,
            "exporter"           => new HtmlExporter($result, $report)
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
}