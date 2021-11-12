<?php

namespace Revo\Sidecar\ExportFields;

class ExportField
{
    public $model;

    public    $field;
    protected $dependsOnField;
    protected $title;

    public $filterable = false;
    public $sortable = false;
    public $hideMobile = false;

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

    public function getSelectField() : string
    {
        return $this->dependsOnField;
    }

    public function sortable($sortable = true) : self
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function hideMobile($hideMobile = true) : self
    {
        $this->hideMobile = $hideMobile;
        return $this;
    }

    // --------------------------------------------
    // Filterable
    // --------------------------------------------
    public function filterable($filterable = true) : self
    {
        $this->filterable = $filterable;
        return $this;
    }

    public function filterOptions() : array {
        return [];
    }

    public function isNumeric() : bool
    {
        return false;
    }

}