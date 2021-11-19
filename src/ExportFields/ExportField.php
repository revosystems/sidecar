<?php

namespace Revo\Sidecar\ExportFields;
use App\Models\EloquentBuilder;
use Illuminate\Support\Facades\DB;
use Revo\Sidecar\Filters\GroupBy;
use Revo\Sidecar\Filters\Filters;

class ExportField
{
    public $model;

    public    $field;
    protected $dependsOnField;
    protected $title;
    public ?string $icon = null;

    public bool $filterable = false;
    public bool $filterSearchable = false;
    public bool $sortable = false;
    public bool $hidden = false;
    public bool $onlyWhenGrouping = false;

    public bool $hideMobile = false;

    public bool $groupable = false;
    public bool $comparable = false;
    public bool $groupableWithChart = false;
    public string $groupableAggregatedField;
    public string $groupableGraphType;

    public ?string $onTable = null;

    /** @var string The classes used when exporting to html fo the TD field */
    public $tdClasses = "";

    public ?string $onGroupingBy = null;


    public static function make($field, $title = null, $dependsOnField = null)
    {
        $exportField = new static;
        $exportField->field = $field;
        $exportField->title = $title ?? __(config('sidecar.translationsPrefix').$field);
        $exportField->dependsOnField = $dependsOnField ?? $field;
        return $exportField;
    }

    public function toHtml($row) : string {
        return $this->getValue($row) ?? "";
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

    public function getSelectField(?GroupBy $groupBy = null)
    {
        if ($groupBy && $groupBy->isGrouping()){
            if ($groupBy->isGroupingBy($this->dependsOnField)) { return $this->dependsOnField; }
            if ($this->onGroupingBy == null)       { return null; }
            return "{$this->onGroupingBy}({$this->dependOnFieldFull()}) as {$this->dependsOnField}";
        }
        return $this->dependsOnField;
    }

    public function dependOnFieldFull()
    {
        return $this->databaseTableFull(). "." . $this->dependsOnField;
    }

    public function databaseTable(): string {
        return $this->onTable ?? (new $this->model)->getTable();
    }

    public function databaseTableFull() : string {
        return config('database.connections.mysql.prefix') . $this->databaseTable();
    }

    public function getFilterField() : string {
        return $this->getSelectField();
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
    public function filterable($filterable = true, $searchable = false) : self
    {
        $this->filterable = $filterable;
        $this->filterSearchable = $searchable;
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

    public function comparable(string $comparable = 'total') : self
    {
        $this->comparable = $comparable;
        return $this;
    }

    public function groupableWithGraph($aggregatedField = 'total', $type = 'bar') : self
    {
        $this->groupable = true;
        $this->groupableWithChart = true;
        $this->groupableAggregatedField = $aggregatedField;
        $this->groupableGraphType = $type;
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

    public function tdClasses(string $classes) : self
    {
        $this->tdClasses = $classes;
        return $this;
    }

    public function getTDClasses() : string {
        $classes = $this->tdClasses;
        if ($this->hideMobile) { $classes .= " hide-mobile"; }
        if ($this->isNumeric()) { $classes .= " text-right"; }
        return $classes;
    }


    public function isNumeric() : bool
    {
        return false;
    }

    public function applyFilter(Filters $filters, EloquentBuilder $query, $key, $values) : EloquentBuilder
    {
        return $filters->applyFilter($query, $this->databaseTable().'.'.$key, $values);
    }

    public function addJoin(EloquentBuilder $query, Filters $filters, GroupBy $groupBy) : EloquentBuilder
    {
        return $query;
    }

    public function getEagerLoadingRelations()
    {
        return null;
    }

    public function shouldBeEported($filters) : bool
    {
        if ($this->hidden) { return false; }
        if ($filters->groupBy->isGrouping()) {
            return $this->onGroupingBy != null || $filters->groupBy->isGroupingBy($this->getFilterField());
        }
        return !$this->onlyWhenGrouping;
    }

    public function onTable(?string $table) : self
    {
        $this->onTable = $table;
        return $this;

    }
}