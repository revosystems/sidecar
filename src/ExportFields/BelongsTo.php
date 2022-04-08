<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;

class BelongsTo extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];
    protected bool $defaultJoin = false;
    protected bool $useLeftJoin = false;

    protected ?string $linkField = null;

    protected $filterOptions = null;
    protected ?array $filterOptionsIds = null;

    public function getValue($row)
    {
        return data_get($row, "{$this->field}.{$this->relationShipField}") ?? "--";
    }

    public function getFilterId($row)
    {
        return data_get($row, $this->foreingKey());
    }

    public function relationShipDisplayField(string $relationShipDisplayField) : self {
        $this->relationShipField = $relationShipDisplayField;
        return $this;
    }

    public function getSelectField(?GroupBy $groupBy = null) : ?string
    {
        $foreingKey = $this->foreingKey();
        if ($groupBy && $groupBy->isGrouping() && !$groupBy->isGroupingBy($foreingKey)) { return null; }
        return $foreingKey;
    }

    public function defaultJoin($defaultJoin = true) : self
    {
        $this->defaultJoin = $defaultJoin;
        return $this;
    }

    public function withLeftJoin(bool $leftJoin = true) : self {
        $this->useLeftJoin = $leftJoin;
        return $this;
    }

    private function foreingKey() : string {
        return $this->relation()->getForeignKeyName();
    }

    public function getFilterField() : string
    {
        return $this->getSelectField();
    }

    public function relationShipWith(array $with) : self
    {
        $this->relationShipWith = $with;
        return $this;
    }

    public function filterOptions(?Filters $filters = null) : array
    {
        if (!$this->filterOptions) {
            $query = $this->relation()->getRelated()->with($this->relationShipWith);
            if ($this->filterOptionsIds){
                $query->whereIn('id', $this->filterOptionsIds);
            }
            if ($this->filterSearchable) {
                $in = ($filters ?? new Filters())->filtersFor($this->getFilterField());
                if ($in->count() == 0) return [];
                $query->whereIn('id', $in);
            }
            $this->filterOptions = $query->get([$this->relationShipField, 'id'])->pluck($this->relationShipField, 'id')->all();
        }
        return $this->filterOptions;
    }

    /**
     * @param $ids add the filter scope ids to filter
     * @return $this
     */
    public function filterOptionsIdsScope($ids) : self {
        $this->filterOptionsIds = $ids;
        return $this;
    }

    public function searchableRoute() : string
    {
        $searchClass = get_class($this->relation()->getRelated());
        return route('sidecar.search.model', ["model" => $searchClass, "field" => $this->relationShipField , "onlyIds" => $this->filterOptionsIds]);
    }

    protected function relation(){
        return (new $this->model)->{$this->field}();
    }

    public function getEagerLoadingRelations()
    {
        return $this->field;
    }

    public function toHtml($row): string
    {
        if($this->route){
            return link_to_route($this->route, $this->getValue($row), data_get($row, $this->linkField), ['class' => $this->linkClasses]);
        }
        return parent::toHtml($row);
    }

    public function withLink($route, $linkClasses = null, $linkField = null) : self
    {
        $this->route = $route;
        $this->linkClasses = $linkClasses;
        $this->linkField    = $linkField ?? $this->foreingKey();
        return $this;
    }

    public function addJoin(Builder $query, Filters $filters): Builder
    {
        if (!$this->defaultJoin) { return $query; }

        $main = (new $this->model)->getTable();
        $joinWith = $this->relation()->getRelated()->getTable();
        $foreignKey = $this->relation()->getForeignKeyName();
        if ($this->useLeftJoin) {
            return $query->leftJoin($joinWith, "{$main}.{$foreignKey}", "{$joinWith}.id");
        }
        return $query->join($joinWith, "{$main}.{$foreignKey}", "{$joinWith}.id");
    }

}