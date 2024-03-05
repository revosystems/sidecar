<?php

namespace Revo\Sidecar\ExportFields;

use Revo\Sidecar\Filters\GroupBy;
use Illuminate\Support\Facades\DB;

class BusinessDateTime extends Date
{
    public function getNonGroupedValue($value) : string {
        return $this->getCarbonDate($value)->isoFormat('L HH:mm');
    }

    /**
     * @param  GroupBy|null  $groupBy
     * @return string|null the select field (or array of select fields) to include in the query
     */
    public function getSelectField(?GroupBy $groupBy = null)
    {
        $timezone    = Date::$timezone;
        $openingTime = Date::$openingTime;

        if ($this->computed) {
            return $this->getComputedSelectField($groupBy);
        }

        if ($groupBy?->isGrouping()) {            
            if ($groupBy->isGroupingBy($this->dependsOnField, 'hour')) {
                return $this->dependsOnField;
            }
            if ($groupBy->isGroupingBy($this->dependsOnField)) {
                return DB::raw("DATE(SUBTIME(CONVERT_TZ({$this->dependsOnField}, 'UTC', '{$timezone}'), '{$openingTime}')) as {$this->dependsOnField}");
            }

            return $this->onGroupingBy
                ? "{$this->onGroupingBy}({$this->dependOnFieldFull()}) as ".collect(explode(".",
                    $this->dependsOnField))->last()
                : null;
        }

        return $this->dependOnFieldFull();
    }
}