<?php

namespace Revo\Sidecar\ExportFields;
use App\Models\EloquentBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
    public bool $onlyWhenGrouping = false;

    public bool $groupable = false;
    public bool $comparable = false;
    public bool $groupableWithChart = false;
    public string $groupableAggregatedField;
    public string $groupableGraphType;
    public ?string $onGroupingBy = null;

    public ?string $onTable = null;

    /** @var string The classes used when exporting to html fo the TD field */
    public $tdClasses = "";
    public bool $hideMobile = false;
    public bool $hidden = false;
    public bool $filterOnClick = false;


    public static function make($field, $title = null, $dependsOnField = null)
    {
        $exportField = new static;
        $exportField->field = $field;
        $exportField->title = $title ?? __(config('sidecar.translationsPrefix').$field);
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

    public function getSelectField(?GroupBy $groupBy = null)
    {
        if ($groupBy && $groupBy->isGrouping()){
            if ($groupBy->isGroupingBy($this->dependsOnField)) { return $this->dependsOnField; }
            if ($this->onGroupingBy == null)       { return null; }
            //return "{$this->onGroupingBy}({$this->dependOnFieldFull()}) as {$this->dependsOnField}";
            $as = collect(explode(".", $this->dependsOnField))->last();
            return "{$this->onGroupingBy}({$this->dependOnFieldFull()}) as {$as}";
        }
        return $this->dependOnFieldFull();
//        return $this->dependsOnField;
    }

    public function dependOnFieldFull() : string
    {
        if (Str::contains($this->dependsOnField, config('database.connections.mysql.prefix'))){
            return $this->dependsOnField;
        }
        return $this->databaseTableFull(). "." . $this->dependsOnField;
    }

    public function databaseTable(): string {
        return $this->onTable ?? (new $this->model)->getTable();
    }

    public function databaseTableFull() : string {
        return config('database.connections.mysql.prefix') . $this->databaseTable();
    }

    public function getFilterField() : string {
        //return $this->getSelectField();
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

    //============================================================
    // MARK: Filters
    //============================================================
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

    public function onlyWhenGrouping(bool $onlyWhenGrouping = true) : self
    {
        $this->onlyWhenGrouping = $onlyWhenGrouping;
        return $this;
    }

    public function isNumeric() : bool
    {
        return false;
    }

    public function getEagerLoadingRelations()
    {
        return null;
    }

    public function shouldBeExported($filters) : bool
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

    //=================================================
    // MARK: HTML
    //=================================================
    public function toHtml($row) : string {
        $value = $this->getValue($row) ?? "";
        if ($this->filterOnClick){
            return $this->filterLink($row, $value);
        }
        return $value;
    }

    public function filterLink($row, $value)
    {
        return "<a onclick='filterOnClick(\"{$this->getFilterField()}\", {$this->getFilterId($row)})' class='pointer'>{$value}</a>";
    }

    public function getFilterId($row)
    {
        return null;
    }

    public function tdClasses(string $classes) : self {
        $this->tdClasses = $classes;
        return $this;
    }

    public function getTDClasses() : string {
        $classes = $this->tdClasses;
        if ($this->hideMobile) { $classes .= " hide-mobile"; }
        if ($this->isNumeric()) { $classes .= " text-right"; }
        return $classes;
    }

    public function hidden(bool $hidden = true) : self
    {
        $this->hidden = $hidden;
        return $this;
    }

    public function filterOnClick() : self
    {
        $this->filterOnClick = true;
        return $this;
    }

    //============================================================
    // MARK: Filters
    //============================================================
    public function applyFilter(Filters $filters, EloquentBuilder $query, $key, $values) : EloquentBuilder
    {
        return $filters->applyFilter($query, $this->databaseTable().'.'.$key, $values);
    }

    public function applyGroupBy(Filters $filters, EloquentBuilder $query, $key, $type)
    {
        $filters->groupBy->groupBy($query, $this->databaseTableFull().'.'.$key, $type);
    }

    public function applySort(Filters $filters, EloquentBuilder $query){
//        dd($this->databaseTableFull(), $filters->sort->field);
        $filters->sort->sort($query, $this->databaseTableFull().'.'.$filters->sort->field);
    }

    public function addJoin(EloquentBuilder $query, Filters $filters) : EloquentBuilder
    {
        return $query;
    }
}