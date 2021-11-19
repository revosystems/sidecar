<?php

namespace Revo\Sidecar\Widgets;

abstract class Widget
{
    public $model;
    public $onTable = null;

    public $title;
    public $field;
    public $display = 'bigNumber';

    public static function make($field, $title = null)
    {
        $panel = (new static);
        $panel->field = $field;
        $panel->title = $title ?? __(config('sidecar.translationsPrefix').$field);
        return $panel;
    }

    public function onTable($table) : self
    {
        $this->onTable = $table;
        return $this;
    }

    public function displayWith($display) : self {
        $this->display = $display;
        return $this;
    }

    public function getSelectField($groupBy = null) {
        return null;
    }

    public function getValue($row) : string
    {
        return "--";
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function render($row) : string
    {
        return view("sidecar::widgets.{$this->display}",[
            'value' => $this->getValue($row),
            'title' => $this->getTitle()
        ])->render();
    }

    public function fullField() : string {
        return $this->databaseTableFull() .'.'.$this->field;
    }

    public function databaseTable(): string {
        return $this->onTable ?? (new $this->model)->getTable();
    }

    public function databaseTableFull() : string {
        return config('database.connections.mysql.prefix') . $this->databaseTable();
    }
}