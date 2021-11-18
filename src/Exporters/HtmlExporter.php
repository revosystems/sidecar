<?php

namespace Revo\Sidecar\Exporters;

class HtmlExporter extends BaseExporter
{
    protected $output = '';

    public static $tableClasses = "tableList striped";

    public function export() : string {
        return view('sidecar::widgets.table',[
            "tableClasses" => static::$tableClasses,
            "fields" => $this->getFields(),
            "rows" => $this->data,
        ])->render();
    }

    protected function getType()
    {
        return "html";
    }
}