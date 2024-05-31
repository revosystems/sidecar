<?php

namespace Revo\Sidecar;

// [ ] Default joins
// [ ] Add gates / policies
// [ ] Widgets => comprasion value in % with same period before
// [ ] Calculate as job
// [ ] Belongs to throug => with left join no ho ha fet?
// [ ] Date depth hour => filtear per data i hores, sense agrupar llavors
// [ ] Poder omplir tots els dies (amb 0)
// [ ] Panel => comparable
// [ ] Export, borrar els vells
// [ ] Text, filterOnClick no xuta!
//

// https://apps.shopify.com/advanced-reports?locale=es
// https://www.youtube.com/watch?v=FzBHMY8u5aQ

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
    protected ?string $tooltip = null;
    protected $with = [];
    protected int $pagination = 50;
    public bool $exportable = true;
    public bool $canBeSaved = true;

    public Filters $filters;
    public Collection $columns;

    public function __construct(?Filters $filters = null, ?array $columns = null) {
        $this->filters = $filters ?? new Filters();
        $this->columns = collect($columns ?? request('columns'))->filter();
    }

    public function getTitle() : string
    {
        return $this->title ?? trans_choice(config('sidecar.translationsPrefix'). strtolower(collect(explode("\\",$this->model))->last()), 2);
    }

    public function getTooltip() : ?string
    {
        if (!$this->tooltip) { return null; }
        return __(config('sidecar.translationsPrefix').$this->tooltip);
    }

    public function fields() : Collection
    {
        return collect($this->getFields())->each(function (ExportField $field) {
            $field->model = $this->model;
        });
    }

    public function exportableFields() : Collection
    {
        return collect($this->getExportableFields())->each(function (ExportField $field) {
            $field->model = $this->model;
        });
    }

    abstract protected function getFields() : array;

    protected function getExportableFields() : array
    {
        return $this->getFields();
    }

    public function widgets() : Collection
    {
        return collect($this->getWidgets())->each(function (Widget $widget){
            $widget->model = $this->model;
        });
    }
    public function getWidgets() : array { return []; }

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
            if (!Str::contains($selectField, '.') && !Str::contains($selectField, 'as') && !Str::contains($selectField, config('database.connections.mysql.prefix'))){
                $selectField = "{$modelTable}.{$selectField}";
            }
            return DB::raw($selectField);
        })->unique()->all();
    }

    public function getWidgetsSelectFields($groupBy)
    {
        $modelTable = $this->getModelTable();
        return $this->widgets()->map(function(Widget $widget) use ($groupBy){
            return $widget->getSelectField($groupBy);
        })->flatten()->filter()->map(function($selectField) use($modelTable){
            if (!Str::contains($selectField, '.') && !Str::contains($selectField, 'as') && !Str::contains($selectField, config('database.connections.mysql.prefix'))){
                $selectField = "{$modelTable}.{$selectField}";
            }
            return DB::raw($selectField);
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

    public function isComparable(): bool {
        if  (!$this->filters->groupBy->canBeCompared()) { return false; };
        $compareKey = $this->filters->groupBy->groupings->keys()->first();
        return $this->fields()->contains(function (ExportField $field) use($compareKey) {
            return $field->getFilterField() == $compareKey;
        });
    }

    public function mainActions(): array
    {
        return [];
    }

    //==================================================
    // QUERY
    //==================================================
    public function query() : Builder {
        return $this->model::with(array_merge($this->with, $this->findEagerLoadingNeedeRelationShips()));
    }

    public function queryWithFilters() : Builder
    {
        return $this->filters->apply($this->query(), $this->fields())
            ->select($this->getSelectFields($this->filters->groupBy));
    }

    public function paginate($pagination = null) {
//        dd($this->filters, $this->queryWithFilters()->toSql());
        return $this->queryWithFilters()->paginate($pagination ?? $this->pagination)->withQueryString();
    }

    public function get()
    {
//        dd($this->filters, $this->queryWithFilters()->toSql());
        return $this->queryWithFilters()->get();
    }
}
