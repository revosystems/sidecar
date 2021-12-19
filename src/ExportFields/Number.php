<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use phpDocumentor\Reflection\Types\Parent_;
use Revo\Sidecar\Filters\Filters;

class Number extends ExportField
{
    protected $trimZeros = false;

    public static function make($field, $title = null, $dependsOnField = null)
    {
        return parent::make($field, $title, $dependsOnField)->onGroupingBy('sum');
    }

    public function isNumeric() : bool
    {
        return true;
    }

    public function getValue($row)
    {
        $value = parent::getValue($row);
        if ($this->trimZeros){
            return $this->trimTrailingZeros($value);
        }
        return $value;
    }

    public function trimZeros($trimZeros = true) : self
    {
        $this->trimZeros = $trimZeros;
        return $this;
    }

    private function trimTrailingZeros($num){
        if (strpos($num, '.') === false) {
            return $num;
        }
        return rtrim(rtrim($num, '0'), '.');
    }

    public function filterOptions() : array
    {
        return [
            "=" => "=",
            ">" => ">",
            ">=" => ">=",
            "<" => "<",
            "<=" => "<=",
            "<>" => "<>",
        ];
    }

    public function applySort(Filters $filters, Builder $query)
    {
        if (!$filters->groupBy->isGrouping()){
            return parent::applySort($filters, $query);
        }
        $filters->sort->sort($query, $filters->sort->field);
    }

    public function applyFilter(Filters $filters, Builder $query, $key, $values): Builder
    {
        if ($values['value'] == null) { return $query; }
        //dd($values['operand'], $values['value']);
        return $query->where($this->databaseTable().'.'.$key, $values['operand'], $values['value']);
    }
}