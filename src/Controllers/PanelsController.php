<?php

namespace Revo\Sidecar\Controllers;

use Revo\Sidecar\Compare;
use Revo\Sidecar\Exporters\HtmlExporter;
use Revo\Sidecar\Facades\Sidecar;
use Revo\Sidecar\Filters\Graph;

class PanelsController
{
    public function show($class)
    {
        return (new $class)->renderCalculated();
    }
}