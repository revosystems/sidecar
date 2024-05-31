<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Revo\Sidecar\Filters\GroupBy;
use Revo\Sidecar\Filters\Filters;

class ExportField
{
    public $model;

    public $field;
    protected ?string $computed = null;
    protected $dependsOnField;
    protected $title;
    protected ?string $tooltip = null;
    protected ?string $filterTooltip = null;
    public ?string $icon = null;

    public bool $filterable = false;
    public bool $filterSearchable = false;
    public bool $sortable = false;
    public bool $onlyWhenGrouping = false;

    public bool $groupable = false;
    //public bool $comparable = false;
    public bool $groupableWithChart = false;
    public string $groupableAggregatedField;
    public string $groupableGraphType;
    public ?string $onGroupingBy = null;

    public ?string $onTable = null;

    /** @var string The classes used when exporting to html fo the TD field */
    public $tdClasses = "";
    public ?string $displayFrom = null;
    public bool $hidden = false;
    public bool $hideTitle = false;
    public bool $filterOnClick = false;
    public ?string $route = null;
    protected ?string $linkClasses = "";
    
    public static function make($field, $title = null, $dependsOnField = null)
    {
        $exportField = new static;
        $exportField->field = $field;
        $exportField->title = $title ?? __(config('sidecar.translationsPrefix').$field);
        $exportField->dependsOnField = $dependsOnField ?? $field;
        return $exportField;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function exportTitle(): string
    {
        return $this->getTitle();
    }

    public function getTooltip(): ?string
    {
        return $this->tooltip;
    }

    public function getFilterTooltip(): ?string
    {
        return $this->filterTooltip ?? $this->getTitle();
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getValue($row)
    {
        return data_get($row, $this->field);
    }

    /**
     * @param  GroupBy|null  $groupBy
     * @return string|null the select field (or array of select fields) to include in the query
     */
    public function getSelectField(?GroupBy $groupBy = null)
    {
        if ($this->computed) {
            return $this->getComputedSelectField($groupBy);
        }

        if ($groupBy?->isGrouping()) {
            if ($groupBy->isGroupingBy($this->dependsOnField)) {
                return $this->dependsOnField;
            }
            return $this->onGroupingBy
                ? "{$this->onGroupingBy}({$this->dependOnFieldFull()}) as ".collect(explode(".",
                    $this->dependsOnField))->last()
                : null;
        }
        return $this->dependOnFieldFull();
    }

    public function getComputedSelectField(?GroupBy $groupBy = null): ?string
    {
        if ($groupBy && $groupBy->isGrouping()) {
            return $this->onGroupingBy
                ? "({$this->onGroupingBy}) as {$this->field}"
                : null;
        }
        return "({$this->computed}) as {$this->field}";
    }

    public function dependOnFieldFull(): string
    {
        if (Str::contains($this->dependsOnField, config('database.connections.mysql.prefix'))) {
            return $this->dependsOnField;
        }
        return $this->databaseTableFull().".".$this->dependsOnField;
    }

    public function databaseTable(): string
    {
        return $this->onTable ?? (new $this->model)->getTable();
    }

    public function databaseTableFull(): string
    {
        return config('database.connections.mysql.prefix').$this->databaseTable();
    }

    public function getFilterField(): string
    {
        //return $this->getSelectField();
        return $this->dependsOnField;
    }

    public function sortable($sortable = true): self
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function hideMobile(): self
    {
        $this->displayFrom = 'lg';
        return $this;
    }

    public function displayFrom(string $from) : self {
        $this->displayFrom = $from;
        return $this;
    }

    public function computed(string $query): self
    {
        $this->computed = $query;
        return $this;
    }

    public function onGroupingBy(?string $action): self
    {
        $this->onGroupingBy = $action;
        return $this;
    }

    public function mapValue(mixed $value): mixed
    {
        return $value;
    }

    //============================================================
    // MARK: Filters
    //============================================================
    public function filterable($filterable = true, $searchable = false): self
    {
        $this->filterable = $filterable;
        $this->filterSearchable = $searchable;
        return $this;
    }

    public function icon(?string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function filterOptions(): array
    {
        return [];
    }

    public function groupable(bool $groupable = true): self
    {
        $this->groupable = $groupable;
        return $this;
    }

    public function groupableWithGraph($aggregatedField = 'total', $type = 'bar'): self
    {
        $this->groupable = true;
        $this->groupableWithChart = true;
        $this->groupableAggregatedField = $aggregatedField;
        $this->groupableGraphType = $type;
        return $this;
    }

    public function comparable(string $comparable = 'total'): self
    {
        $this->comparable = $comparable;
        return $this;
    }

    public function groupings(): array
    {
        return ['default'];
    }

    public function onlyWhenGrouping(bool $onlyWhenGrouping = true): self
    {
        $this->onlyWhenGrouping = $onlyWhenGrouping;
        return $this;
    }

    public function isNumeric(): bool
    {
        return false;
    }

    public function getEagerLoadingRelations()
    {
        return null;
    }

    public function shouldBeExported($filters): bool
    {
        if ($this->hidden) {
            return false;
        }
        if ($filters->groupBy->isGrouping()) {
            return $this->onGroupingBy != null || $filters->groupBy->isGroupingBy($this->getFilterField());
        }
        return !$this->onlyWhenGrouping;
    }

    public function withTooltip($tooltip): self
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    public function withFilterTooltip($tooltip): self
    {
        $this->filterTooltip = $tooltip;
        return $this;
    }

    public function onTable(?string $table): self
    {
        $this->onTable = $table;
        return $this;
    }

    public function toCsv($row)
    {
        $value = $this->getValue($row);

        if (is_string($value)) {
            return preg_replace('/\n+/', '', trim($value));
        }

        if (is_array($value)) {
            return implode(',', $value);
        }

        return $value;
    }

    //=================================================
    // MARK: HTML
    //=================================================
    public function toHtml($row): string
    {
        $value = $this->getValue($row) ?? "";
        if ($this->filterOnClick) {
            return $this->filterLink($row, $value);
        }
        if ($this->route) {
            return view('sidecar::fields.link',[
                'url' => route($this->route, $value),
                'title' => $value,
                'classes' => $this->linkClasses
            ])->render();
        }
        return $value;
    }

    public function filterLink($row, $value)
    {
        return view('sidecar::fields.filterOnClick',[
            'field' => $this->getFilterField(),
            'filterId' => $this->getFilterId($row),
            'title' => $value,
        ])->render();
    }

    public function getFilterId($row)
    {
        return null;
    }

    public function tdClasses(string $classes): self
    {
        $this->tdClasses = $classes;
        return $this;
    }

    public function getTDClasses(): string
    {
        $classes = $this->tdClasses;
        if ($this->displayFrom) {
            $classes .= " hidden {$this->displayFrom}:table-cell";
        }
        if ($this->isNumeric()) {
            $classes .= " text-right";
        }
        return $classes;
    }

    public function hidden(bool $hidden = true): self
    {
        $this->hidden = $hidden;
        return $this;
    }

    public function filterOnClick($searchable = true): self
    {
        $this->filterOnClick = true;
        $this->filterable = true;
        $this->filterSearchable = $searchable;
        return $this;
    }

    public function route($route, $classes = null): self
    {
        $this->route = $route;
        $this->linkClasses = $classes;
        return $this;
    }

    public function linkClasses($classes): self
    {
        $this->linkClasses = $classes;
        return $this;
    }

    //============================================================
    // MARK: Filters
    //============================================================
    public function applyFilter(Filters $filters, Builder $query, $key, $values): Builder
    {
        return $filters->applyFilter($query, $this->databaseTable().'.'.$key, $values);
    }

    public function applySearch(Filters $filters, Builder $query, $key, $values): Builder
    {
        return $filters->applySearch($query, $this->databaseTable().'.'.$key, $values);
    }

    public function applyGroupBy(Filters $filters, Builder $query, $key, $type)
    {
        $filters->groupBy->groupBy($query, $this->databaseTableFull().'.'.$key, $type);
    }


    public function applySort(Filters $filters, Builder $query)
    {
//        dd($this->databaseTableFull(), $filters->sort->field);
        $filters->sort->sort($query, $this->databaseTableFull().'.'.$filters->sort->field);
    }

    public function addJoin(Builder $query, Filters $filters): Builder
    {
        return $query;
    }
}