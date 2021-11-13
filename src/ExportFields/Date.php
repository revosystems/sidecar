<?php

namespace Revo\Sidecar\ExportFields;

use Carbon\Carbon;

class Date extends ExportField
{
    static $timezone = "Europe/Madrid";

    public function getValue($row)
    {
        $value = parent::getValue($row);

        if (! $value || $value == "--") {
            return "--";
        }
        return $this->getCarbonDate($value)->toDateString();
    }

    protected function getCarbonDate($value) : Carbon {
        return Carbon::parse($value)->timezone(static::$timezone);
    }
}