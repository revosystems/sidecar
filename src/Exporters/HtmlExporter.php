<?php

namespace Revo\Sidecar\Exporters;

class HtmlExporter extends BaseExporter
{

    protected $output = '';

    public static $tableClasses = "tableList striped";


    public function export() : string {
        $this->init();
        $this->generate();
        $this->finalize();
        return $this->output;
    }

    public function init()
    {
        $this->output .= "<table class='" . static::$tableClasses . "'>";
    }

    protected function finalize()
    {
        $this->output .= "</table>";
    }

    protected function generate()
    {
        $this->addHeader();
        $this->addBody();
    }

    protected function addHeader()
    {
//        $params = http_build_query(Filters::all());
        $this->output .= $this->getFields()->reduce(function ($carry, $field){
            //$classes = $field->hideMobile ? "hide-mobile" : "";
            //if ($field->isNumeric())  {
            //    $classes = "{$classes} text-right";
            //}
            //if (! $field->sortable) {
            //    return $carry . "<th class='{$classes}'>{$field->getTitle()}</th>";
            //}
            //$url = QueryUrl::addQueryToUrl(request()->url() . "?{$params}", ["sort" => $field->sortable !== true ? $field->sortable : $field->field]);
            $classes = "";
//            return $carry . "<th class='{$classes}'><div class='sortableHeader " . ($field->isNumeric() ? "sortableHeaderRight" : "") . "'>{$field->getTitle()}<div class='sortArrows'><a href='{$url}&sort_order=desc' class='sortUp'>▲</a><a href='{$url}&sort_order=asc' class='sortDown'>▼</a></div></div></th>";
            return $carry . "<th class='{$classes}'><div class='sortableHeader '>{$field->getTitle()}<div class='sortArrows'><a href='&sort_order=desc' class='sortUp'>▲</a><a href='&sort_order=asc' class='sortDown'>▼</a></div></div></th>";
        }, "<thead class='sticky'><tr>");
        $this->output .= "</tr></thead>";
    }

    protected function addBody()
    {
        $this->output .= "<tbody>";
        $this->forEachRecord(function ($row) {
            $this->output .= "<tr>";
            foreach ($this->getFields() as $field) {
//                $classes = $field->hideMobile ? "hide-mobile" : "";
                $value = $field->getValue($row);
//                if ($field->isNumeric())  {
//                    $classes = "{$classes} text-right";
//                }
                $classes = '';
                $this->output .= "<td class='{$classes}'>{$value}</td>";
            }
            $this->output .= "</tr>";
        });
        $this->output .= "</tbody>";
    }

    protected function getType()
    {
        return "html";
    }
}