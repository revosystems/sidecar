<?php

namespace Revo\Sidecar\ExportFields;

use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\GroupBy;

class HasMany extends ExportField
{
    protected string $relationShipField = 'name';
    protected array $relationShipWith = [];

//    public ?string $route = null;
//    protected ?string $linkClasses = null;
    protected bool $count = true;

    public function getValue($row)
    {
        if ($this->count) {
            return data_get($row, $this->field)->count();
        }
        return data_get($row, $this->field)->pluck($this->relationShipField)->implode(", ");
    }

    public function relationShipDisplayField(string $relationShipDisplayField) : self {
        $this->relationShipField = $relationShipDisplayField;
        return $this;
    }

    public function getSelectField(?GroupBy $groupBy = null) : ?string
    {
        if ($groupBy && $groupBy->isGrouping()) { return null; }
        return $this->databaseTableFull() . '.id';
    }

    protected function foreingKey() : string {
        return $this->relation()->getForeignKeyName();
    }

    public function getFilterField() : string
    {
        return "not-filterable";
    }

    public function relationShipWith(array $with) : self
    {
        $this->relationShipWith = $with;
        return $this;
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
            return link_to_route($this->route, $this->getValue($row), $row->{$this->foreingKey()}, ['class' => $this->linkClasses]);
        }
        return parent::toHtml($row);
    }

    /**
     * Define the link that the html version will point to
     * @param $route the route
     * @param null $linkClasses the link classes you want to append to the html tag
     * @return $this
     */
    public function withLink($route, $linkClasses = null) : self
    {
        $this->route = $route;
        $this->linkClasses = $linkClasses;
        return $this;
    }

    /**
     * When true, it will show the count of the hasmany, if not, it will show an implode of the relationshipDisplayField (use carefully)
     * @param $count
     * @return $this
     */
    public function count($count) : self {
        $this->count = $count;
        return $this;
    }

}