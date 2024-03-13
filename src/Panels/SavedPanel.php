<?php

namespace Revo\Sidecar\Panels;

use Illuminate\Support\Str;
use Revo\Sidecar\ExportFields\ExportField;
use Revo\Sidecar\Facades\Sidecar;
use Revo\Sidecar\Filters\Filters;
use Revo\Sidecar\Filters\Graph;
use Revo\Sidecar\Report;

class SavedPanel extends Panel
{
    public Report $report;
    public string $url;

    public function __construct() {
        $this->url = 'http://xef.test/reports/v2/orders?sort=&sort_order=&dates[opened][period]=lastYear&dates[opened][start]=2023-01-01&dates[opened][end]=2023-12-31&compare[period]=custom&compare[start]=&compare[end]=&shouldCompare=false&groupBy[]=table_id:default&filters[id][operand]=>&filters[id][value]=0&filters[table_id-operand]=whereIn&filters[room_id-operand]=whereIn&filters[room_id][]=&filters[tenantUser_id-operand]=whereIn&filters[tenantUser_id][]=&filters[guests][operand]==&filters[guests][value]=&dates[opened][start_time]=&dates[opened][end_time]=&filters[discountAmount][operand]==&filters[discountAmount][value]=&filters[total][operand]==&filters[total][value]=&filters[status-operand]=whereIn&filters[status][]=';
        $model = Str::before(Str::after($this->url, 'reports/v2/'), "?");
        $filters = [];
        parse_str(Str::after($this->url, '?'), $filters);
        $filters['filters'] = collect($filters['filters'])->reject(function($value, $key){
            return (is_array($value) && count($value) == 1 && $value[0] == "");
        })->all();
        $this->report = Sidecar::make(ucFirst($model) . "Report", new Filters($filters));
        parent::__construct("saved report", new Filters($filters));
    }

    public function renderCalculated(): string
    {
        $result = $this->report->paginate();
        $graph = (new Graph($this->report, $result))->calculate();
        return view('sidecar::graphs.graph-in-panel',[
            'panel' => $this,
            'graph' => $graph
        ])->render();
    }

    public function dimensionField(): ExportField
    {
        // TODO: Implement dimensionField() method.
    }

    public function metricField(): ExportField
    {
        // TODO: Implement metricField() method.
    }

    public function getFullReportLink(): ?string
    {
        return $this->url;
    }
}