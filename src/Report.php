<?php

namespace Revo\Sidecar;

// [x] Fix computed => average one not working properly?
// [x] Fix computed (as currency)
// [x] Graph, don't use ajax
// [x] BelongsTo::make('sellingFormatPivot') => filtrar amb pivot
// [x] Date groupable by week
// [x] Widget, anar a buscar la ruta que toca
// [ ] Widgets take into account filters
// [ ] Filterable => Quants molts, amb un searchable
// [ ] Filterable => Searchable (ajax)
// [ ] Default joins
// [ ] Add gates / policies
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;
use Revo\Sidecar\Widgets\Widget;

abstract class Report
{
    protected $model;
    protected ?string $title = null;
    protected $with = [];
    protected int $pagination = 50;

    public Filters $filters;

    public function __construct(?Filters $filters = null) {
        $this->filters = $filters ?? new Filters();
    }

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

    public function query() : Builder {
        return $this->model::with(array_merge($this->with, $this->findEagerLoadingNeedeRelationShips()));
    }

    public function queryWithFilters() : Builder
    {
        return ($this->filters)->apply($this->query(), $this->fields())
                         ->select($this->getSelectFields($this->filters->groupBy));
    }

    public function paginate($pagination = null) {
//        dd($this->queryWithFilters()->toSql());
        return $this->queryWithFilters()->paginate($pagination ?? $this->pagination)->withQueryString();
    }

    public function widgetsQuery()
    {
        return ($this->filters)->apply($this->query(), $this->fields())
                         ->select($this->getWidgetsSelectFields($this->filters->groupBy));
    }

    public function getSelectFields(?GroupBy $groupBy)
    {
        $modelTable = $this->getModelTable();
        return collect($this->fields())->reject(function(ExportField $field) use($groupBy) {
            return $field->onlyWhenGrouping && !$groupBy->isGrouping();
        })->map(function (ExportField $exportField) use($groupBy){
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