<?php

namespace Revo\Sidecar\ExportFields;

use Carbon\Carbon;
use Illuminate\View\ComponentAttributeBag;
use Revo\Sidecar\Filters\Filters;

class DateTime extends Date
{
    public function getNonGroupedValue($value) : string {
        return view('sidecar::fields.datetime', [
            'date' => $this->getCarbonDate($value)
        ])->render();
    }
}