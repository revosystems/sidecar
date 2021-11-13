<?php

namespace Revo\Sidecar\ExportFields;

use Carbon\Carbon;

class DateTime extends Date
{
    public function getValue($row)
    {
        $value = parent::getValue($row);

        if (! $value || $value == "--") {
            return "--";
        }
        return $this->getCarbonDate($value)->toDateTimeString();
    }
}