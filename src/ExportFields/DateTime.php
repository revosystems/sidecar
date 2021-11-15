<?php

namespace Revo\Sidecar\ExportFields;

use Carbon\Carbon;

class DateTime extends Date
{
    public function getNonGroupedValue($value) : string {
        return $this->getCarbonDate($value)->toDateTimeString();
    }
}