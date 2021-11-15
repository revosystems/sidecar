<?php

namespace Revo\Sidecar;

// [ ] BelongsTo::make('sellingFormatPivot') => filtrar amb pivot
// [ ] Filterable => Quants molts, amb un searchable
// [ ] Filterable => Searchable (ajax)
// [ ] Fix computed (as currency)
// [ ] Fix computed => average one not working properly?
// [ ] Default joins
// [ ] Add gates / policies

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Widgets\Widget;

abstract class Report
{
    protected $model;
    protected $title = null;
    protected $with = [];
    protected $pagination = 50;


    public function getTitle() : string
    {
        return $this->title ?? $this->model;
    }

    public function fields() : \Illuminate\Support\Collection {
        return collect($this->getFields())->each(function (ExportField $field){
            $field->model = $this->model;
        });
    }

    abstract protected function getFields() : array;
    public function getWidgets() : array { return []; }

    public function query(){
        return $this->model::with(array_merge($this->with, $this->findEagerLoadingNeedeRelationShips()));
    }

    public function queryWithFilters()
    {
        $filters = new Filters();
        return ($filters)->apply($this->query(), $this->fields())
                         ->select($this->getSelectFields($filters->groupBy));
    }

    public function paginate($pagination = null) {
        return $this->queryWithFilters()->paginate($pagination ?? $this->pagination)->withQueryString();
    }

    public function widgetsQuery()
    {
        $filters = new Filters();
        return ($filters)->apply($this->query(), $this->fields())
                         ->select($this->getWidgetsSelectFields($filters->groupBy));
    }

    public function getSelectFields(?string $groupBy)
    {
        $modelTable = $this->getModelTable();
        return collect($this->fields())->map(function (ExportField $exportField) use($groupBy){
            return $exportField->getSelectField($groupBy);
        })->flatten()->filter()->unique()->map(function($selectField) use($modelTable){
            if (!Str::contains($selectField, '.') && !Str::contains($selectField, 'as')){
                $selectField = "{$modelTable}.{$selectField}";
            }
            return DB::raw($selectField);
        })->all();
    }

    public function getWidgetsSelectFields($groupBy)
    {
        return collect($this->getWidgets())->map(function(Widget $widget) use ($groupBy){
            return $widget->getSelectField($groupBy);
        })->flatten()->filter()->map(function($selectQuery){
            return DB::raw($selectQuery);
        })->all();
    }

    public function availableFilters()
    {
        return collect($this->fields())->filter(function(ExportField $field){
           return $field->filterable;
        });
    }

    public function availableGroupings() {
        return collect($this->fields())->filter(function(ExportField $field){
            return $field->groupable;
        });
    }

    public function getModelTable(): string {
        return config('database.connections.mysql.prefix') . (new $this->model)->getTable();
    }

    public function findEagerLoadingNeedeRelationShips()
    {
        return $this->fields()->map->getEagerLoadingRelations()->flatten()->filter()->unique()->all();
    }

}