<?php

namespace Revo\Sidecar;

// [x] Currency, fer-ho com a thrust
// [x] Date timezone => Fer-ho com a thrust, que es passa al fer el serving
// [x] Groups by
// [x] Group by => opening time
// [ ] BelongsTo::make('sellingFormatPivot') => filtrar amb pivot
// [ ] Autodiscovery
// [ ] Config => Reports path
// [ ] Autodiscovery recursive
// [ ] Generate with automatically
// [ ] Filterable => Quants folts, amb un searchable
// [ ] Filterable => Searchable (ajax)
// [ ] Fix computed (as currency)
// [ ] Fix computed => average one not working properly?
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $filters = new Filters();
        return ($filters)->apply($this->query(), $this->fields())
                         ->select($this->getSelectFields($filters->groupBy));
    }

    public function paginate($pagination = 25)
    {
        return $this->queryWithFilters()->paginate($pagination);
    }

    public function getSelectFields(?string $groupBy)
    {
        $modelTable = config('database.connections.mysql.prefix').(new $this->model)->getTable();
        return collect($this->fields())->map(function (ExportField $exportField) use($groupBy){
            return $exportField->getSelectField($groupBy);
        })->filter()->unique()->map(function($selectField) use($modelTable){
            if (!Str::contains($selectField, '.') && !Str::contains($selectField, 'as')){
                $selectField =  "{$modelTable}.{$selectField}";
            }
            return DB::raw($selectField);
        })->all();
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

    public function availableGroupings(){
        return collect($this->fields())->filter(function(ExportField $field){
            return $field->groupable;
        });
    }

}