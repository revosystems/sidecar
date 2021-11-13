<?php

namespace Revo\Sidecar\ExportFields;
use Illuminate\Support\Facades\DB;

class ExportField
{
    public $model;

    public    $field;
    protected $dependsOnField;
    protected $title;
    public ?string $icon = null;

    public bool $filterable = false;
    public bool $groupable = false;
    public bool $sortable = false;
    public bool $hidden = false;
    public bool $onlyWhenGrouping = false;

    public bool $hideMobile = false;

    public ?string $onGroupingBy = null;


    public static function make($field, $title = null, $dependsOnField = null)
    {
        $exportField = new static;
        $exportField->field = $field;
        $exportField->title = $title ?? $field;
        $exportField->dependsOnField = $dependsOnField ?? $field;
        return $exportField;
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function getIcon() :?string {
        return $this->icon;
    }

    public function getValue($row) {
        return data_get($row, $this->field);
    }

    public function getSelectField(?string $groupBy = null) : ?string
    {
        if ($groupBy){
            if ($groupBy == $this->dependsOnField) { return $this->dependsOnField; }
            if ($this->onGroupingBy == null)       { return null; }
            return "{$this->onGroupingBy}({$this->dependsOnField}) as {$this->dependsOnField}";
        }
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

    public function onGroupingBy(?string $action) : self
    {
        $this->onGroupingBy = $action;
        return $this;
    }

    // --------------------------------------------
    // Filterable
    // --------------------------------------------
    public function filterable($filterable = true, $icon = null) : self
    {
        $this->filterable = $filterable;
        $this->filterIcon = $icon;
        return $this;
    }

    public function icon(?string $icon) : self
    {
        $this->icon = $icon;
        return $this;
    }

    public function filterOptions() : array {
        return [];
    }

    public function groupable(bool $groupable = true) : self
    {
        $this->groupable = $groupable;
        return $this;
    }

    public function groupings() : array
    {
        return ['default'];
    }

    public function hidden(bool $hidden = true) : self
    {
        $this->hidden = $hidden;
        return $this;
    }

    public function onlyWhenGrouping(bool $onlyWhenGrouping = true) : self
    {
        $this->onlyWhenGrouping = $onlyWhenGrouping;
        return $this;
    }

    public function isNumeric() : bool
    {
        return false;
    }

}