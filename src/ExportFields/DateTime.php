<?php

namespace Revo\Sidecar\ExportFields;

use Carbon\Carbon;
use Revo\Sidecar\Filters\Filters;

class DateTime extends Date
{
    public function getNonGroupedValue($value) : string {
        //return $this->getCarbonDate($value)->toDateTimeString();
        return $this->getCarbonDate($value)->isoFormat('L HH:mm');
    }
}