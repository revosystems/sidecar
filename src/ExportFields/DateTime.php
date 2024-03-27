<?php

namespace Revo\Sidecar\ExportFields;

use Carbon\Carbon;
use Illuminate\View\ComponentAttributeBag;
use Revo\Sidecar\Filters\Filters;

class DateTime extends Date
{
    protected $withSeconds = false;
    
    public function withSeconds($withSeconds = true) : self {
        $this->withSeconds = $withSeconds;
        return $this;
    }

    public function getNonGroupedValue($value) : string {
        return view('sidecar::fields.datetime', [
            'date' => $this->getCarbonDate($value),
            'withSeconds' => $this->withSeconds
        ])->render();
    }
}