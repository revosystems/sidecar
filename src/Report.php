<?php

namespace Revo\Sidecar;

// [ ] Autodiscovery
// [ ] Config => layout
// [ ] Config => Reports path
// [ ] Autodiscovery recursive
use Revo\Sidecar\Exporters\ReportExporter;
use Revo\Sidecar\ExportFields\ExportField;

class Report
{
    protected $model;
    protected $exporterClass;
    protected $with = [];

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
        return collect($this->getExporter()->getFields())->map(function (ExportField $exportField){
            return $exportField->getSelectField();
        })->unique()->all();
    }

    public function getExporter() : ReportExporter
    {
        return new $this->exporterClass;
    }

}