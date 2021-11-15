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
            "withWidgets"        => count($report->widgets()) > 0,
            "availableFilters"   => $report->availableFilters(),
            "availableGroupings" => $report->availableGroupings(),
            "exporter"           => new HtmlExporter($result, $report)
        ]);
    }

    public function widgets($model){
        $report = Sidecar::make(ucFirst($model) . "Report");
        $widgetsResult = $report->widgetsQuery()->first();
        return view("sidecar::widgets", [
            "widgets"            => $report->widgets(),
            "widgetsResult"      => $widgetsResult,
        ]);
    }
}