<?php

namespace Revo\Sidecar\ExportFields;

use App\Models\EloquentBuilder;
use Carbon\Carbon;
use Revo\Sidecar\Filters\Filters;

class DateTime extends Date
{
    public function getNonGroupedValue($value) : string {
        return $this->getCarbonDate($value)->toDateTimeString();
    }
}