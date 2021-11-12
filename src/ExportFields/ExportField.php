<?php

namespace Revo\Sidecar\ExportFields;

class ExportField
{
    protected $field;
    protected $dependsOnField;
    protected $title;

    public static function make($field, $title = null, $dependsOnField = null)
    {
        $exportField = new static;
        $exportField->field = $field;
        $exportField->title = $title ?? $field;
        $exportField->dependsOnField = $dependsOnField ?? $field;
        return $exportField;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getValue($row)
    {
        return data_get($row, $this->field);
    }

    public function getSelectField()
    {
        return $this->dependsOnField;
    }

}