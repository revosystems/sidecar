<?php

namespace Revo\Sidecar;

// [ ] Autodiscovery
// [ ] Config => Reports path
// [ ] Autodiscovery recursive
// [ ] Currency, fer-ho com a thrust
// [ ] Date timezone => Fer-ho com a thrust, que es passa al fer el serving
// [ ] Generate with automatically

use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Filters\Filters;

abstract class Report
{
    protected $model;
    protected $with = [];

    public function fields() : \Illuminate\Support\Collection {
        return collect($this->getFields())->each(function (ExportField $field){
            $field->model = $this->model;
        });
    }

    abstract protected function getFields() : array;

    public function query(){
        return $this->model::with($this->with);
    }

    public function queryWithFilters()
    {
        return (new Filters())->apply($this->query())->select($this->getSelectFields());
    }

    public function paginate($pagination = 25)
    {
        return $this->queryWithFilters()->paginate($pagination);
    }

    public function getSelectFields()
    {
        return collect($this->fields())->map(function (ExportField $exportField){
            return $exportField->getSelectField();
        })->unique()->all();
    }


    //------------------------------------
    // FILTERS
    //------------------------------------
    public function availableFilters()
    {
        return collect($this->fields())->filter(function(ExportField $field){
           return $field->filterable;
        });
    }

}