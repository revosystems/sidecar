<?php

namespace Revo\Sidecar;

// [ ] Autodiscovery
// [ ] Config => Reports path
// [ ] Autodiscovery recursive
// [ ] Currency, fer-ho com a thrust
use Revo\Sidecar\Exporters\ReportExporter;
use Revo\Sidecar\ExportFields\ExportField;

class Report
{
    protected $model;
    protected $exporterClass;
    protected $with = [];

    protected $exporter = null;

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
        return collect($this->getExporter()->fields())->map(function (ExportField $exportField){
            return $exportField->getSelectField();
        })->unique()->all();
    }

    public function getExporter() : ReportExporter
    {
        if (!$this->exporter) {
            $this->exporter = (new $this->exporterClass($this->model));
        }
        return $this->exporter;
    }

    //------------------------------------
    // FILTERS
    //------------------------------------
    public function availableFilters()
    {
        return collect($this->getExporter()->fields())->filter(function(ExportField $field){
           return $field->filterable;
        });
    }

}