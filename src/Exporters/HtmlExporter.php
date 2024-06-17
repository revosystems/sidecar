<?php

namespace Revo\Sidecar\Exporters;

class HtmlExporter extends BaseExporter
{
    protected $output = '';

    public static $tableClasses = "tableList striped";

    public function export() : string {
        return view('sidecar::widgets.table',[
            "report"     => $this->report,
            "tableClasses" => static::$tableClasses,
            "fields" => $this->getFields()->filter(fn($field) => in_array('html', $field->exporters)),
            "rows" => $this->data,
        ])->render();
    }

    protected function getType()
    {
        return "html";
    }
}