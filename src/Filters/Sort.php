<?php

namespace Revo\Sidecar\Filters;

use Illuminate\Database\Eloquent\Builder;
use Revo\Sidecar\ExportFields\ExportField;

class Sort
{

    public ?string $field;
    public ?string $order;

    public function __construct(?string $field, ?string $order)
    {
        $this->field = $field;
        $this->order = $order ?? 'DESC';
    }

    public function sort(Builder $query, $field = null) : Builder
    {
        if ($this->field == null) { return $query; }
        return $query->orderBy($field ?? $this->field, $this->order);
    }

    public static function queryUrlFor(ExportField $field, string $order = 'ASC') : string
    {
        $params = array_merge(request()->query->all(), [
            "sort" => $field->getFilterField(),
            "sort_order" => $order,
        ]);

        return request()->url() . "?" . http_build_query($params);
    }
}