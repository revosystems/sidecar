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
            "availableFilters" => $report->availableFilters(),
            "availableGroupings" => $report->availableGroupings(),
            "exporter" => new HtmlExporter($result, $report)
        ]);
    }
}