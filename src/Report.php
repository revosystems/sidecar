<?php

namespace Revo\Sidecar;

// [ ] Autodiscovery
// [ ] Config => Reports path
// [ ] Autodiscovery recursive
// [ ] Currency, fer-ho com a thrust
use Revo\Sidecar\Exporters\ReportExporter;
use Revo\Sidecar\ExportFields\ExportField;

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
        return $this->globalFilters($this->model::with($this->with));
    }

    protected function globalFilters($query){
        return $query;
    }

    public function paginate($pagination = 25)
    {
        return $this->query()->select($this->getSelectFields())->paginate($pagination);
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