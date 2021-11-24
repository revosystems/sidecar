<?php

namespace Revo\Sidecar\Filters;

use App\Models\EloquentBuilder;
use BadChoice\Thrust\Actions\Export;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Revo\Sidecar\ExportFields\Date;
use Revo\Sidecar\ExportFields\ExportField;
use Illuminate\Support\Facades\DB;

class Filters
{
    public $requestFilters = [];
    public $dates = [];
    public $groupBy;
    public $sort;
    public $limit = null;

    public function __construct() {
        $this->requestFilters = request('filters');
        $this->dates          = request('dates');
        $this->groupBy        = new GroupBy(request('groupBy'));
        $this->sort           = new Sort(request('sort'), request('sort_order'));
    }

    //======================================================================
    // SETUP HELPERS
    //======================================================================
    public function groupingBy($groupings) : self
    {
        $this->groupBy->groupings = collect($groupings);
        return $this;
    }

    public function forDates(string $key, Carbon $start, ?Carbon $end = null) : self {
        $this->dates[$key]['start'] = $start->toDateString();
        $this->dates[$key]['end'] = ($end ?? $start)->toDateString();
        return $this;
    }

    public function sortBy($key, $order = 'DESC') : self {
        $this->sort->field = $key;
        $this->sort->order = $order;
        return $this;
    }

    public function limit($limit) : self {
        $this->limit = $limit;
        return $this;
    }

    //======================================================================
    // LOGIC
    //======================================================================
    public function apply($query, $fields) : EloquentBuilder {
        $this->addFilters($query, $fields)
             ->addJoins($query, $fields)
             ->addGroups($query, $fields)
             ->addSorts($query, $fields)
             ->addLimit($query);
        return $query;
    }

    public function isFilteringBy($key, $value = null) : bool
    {
        if ($value == null) {
            return array_key_exists($key, $this->requestFilters ?? []);
        }
        return in_array($value, $this->requestFilters[$key] ?? []);
    }

    public function addFilters($query, $fields) : self
    {
        $this->fillDatesFieldsWithDefaultDatesWhenEmpty($fields);

        collect($this->requestFilters)->each(function($value, $key) use($query, $fields){
            optional($this->fieldFor($fields, $key))->applyFilter($this, $query, $key, $value);
        });
        collect($this->dates)->each(function($value, $key) use($query, $fields){
            optional($this->fieldFor($fields, $key))->applyFilter($this, $query, $key, $value);
        });
        return $this;
    }

    public function addGroups($query, $fields) : self
    {
        collect($this->groupBy->groupings)->each(function($type, $key) use($query, $fields){
            optional($this->fieldFor($fields, $key))->applyGroupBy($this, $query, $key, $type);
        });
        return $this;
    }

    public function addSorts($query, $fields) : self
    {
        optional($this->fieldFor($fields, $this->sort->field))->applySort($this, $query);
        return $this;
    }

    public function addLimit($query) : self
    {
        if ($this->limit) {
            $query->limit($this->limit);
        }
        return $this;
    }

    protected function fieldFor($fields, $filterField) :?ExportField {
        return $fields->first(function(ExportField $field) use($filterField){
            return $field->getFilterField() == $filterField;
        });
    }

    public function applyFilter($query, $key, $value)
    {
        if (is_array($value)) {
            return $query->whereIn($key, $value);
        }
        $query->where($key, $value);
    }

    private function getDefaultDates() : array{
        return [
            'start' => Carbon::today()->subDays(7)->toDateString(),
            'end' => Carbon::today()->toDateString()
        ];
    }

    public function applyDateFilter($query, $key, $values)
    {
        // As we always want to limit dates, we fill them with one week default value
        /*
        if ($values['start'] == null && $values['end'] == null) { return $query; }
        if ($values['end'] == null) {
            return $query->where($key, '>', $values['start']);
        }
        if ($values['start'] == null) {
            return $query->where($key, '<', $values['end']);
        }*/
        return $query->whereBetween($key, [$values['start'], $values['end']]);
    }

    public function addJoins($query, $fields) : self
    {
        $fields->each(function(ExportField $field) use($query) {
            $field->addJoin($query, $this);
        });
        return $this;
    }

    private function fillDatesFieldsWithDefaultDatesWhenEmpty($fields): void
    {
        $fields->filter(function (ExportField $field) {
            return $field instanceof Date && !isset($this->dates[$field->getFilterField()]);
        })->each(function(ExportField $field){
            $this->dates[$field->getFilterField()] = $this->getDefaultDates();
        });
    }

    public function dateFilterTitleFor(ExportField $field)
    {
        if ($period = $this->datePeriodFilterFor($field)) {
            if ($period != 'custom') {
                return __(config('sidecar.translationsPrefix') . $period);
            }
        }
        return Carbon::parse($this->dateFilterStartFor($field))->format("jS F Y")  ." - " .
               Carbon::parse($this->dateFilterEndFor($field))->format("jS F Y");
    }

    public function datePeriodFilterFor(ExportField $field) : ?string
    {
        return $this->dates[$field->getFilterField()]['period'] ?? null;
    }

    public function dateFilterStartFor(ExportField $field) {
        return $this->dates[$field->getFilterField()]['start'] ?? "";
    }

    public function dateFilterEndFor(ExportField $field) {
        return $this->dates[$field->getFilterField()]['end'] ?? "";
    }
}