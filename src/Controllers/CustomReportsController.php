<?php

namespace Revo\Sidecar\Controllers;

use Cassandra\Custom;
use Illuminate\Support\Facades\Cookie;
use Revo\Sidecar\Compare;
use Revo\Sidecar\Exporters\HtmlExporter;
use Revo\Sidecar\Facades\Sidecar;
use Revo\Sidecar\Filters\Graph;
use Revo\Sidecar\CustomReports;

class CustomReportsController
{

    public function store()
    {
        CustomReports::save(request('name'), request('url'));
        return back()->with(['message' => __(config('sidecar.translationsPrefix').'customReportSaved')]);
    }

    public function delete()
    {
        CustomReports::delete(request('name'));
        return back()->with(['message' => __(config('sidecar.translationsPrefix').'customReportDeleted')]);
    }
}