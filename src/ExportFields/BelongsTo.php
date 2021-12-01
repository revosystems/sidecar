<?php

namespace Revo\Sidecar\ExportFields;

use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;

class BelongsTo extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];

    protected ?string $linkField = null;

    protected $filterOptions = null;

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

    public function filterOptions() : array
    {
        if (!$this->filterOptions) {
            $this->filterOptions = $this->relation()->getRelated()->with($this->relationShipWith)->get()->pluck($this->relationShipField, 'id')->all();
        }
        return $this->filterOptions;
    }

    public function searchableRoute() : string
    {
        $searchClass = get_class($this->relation()->getRelated());
        return route('sidecar.search.model', ["model" => $searchClass, "field" => $this->relationShipField]);
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

}