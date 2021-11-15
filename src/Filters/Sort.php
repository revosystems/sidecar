<?php

namespace Revo\Sidecar\Filters;

use Revo\Sidecar\ExportFields\ExportField;

class Sort
{

    public function sort($query, $field, $order = 'DESC')
    {
        if ($field == null) { return; }
        return $query->orderBy($field, $order);
    }

    public function queryUrlFor(ExportField $field, string $order = 'ASC') : string
    {
        $params = array_merge(request()->query->all(), [
            "sort" => $field->getFilterField(),
            "sort_order" => $order,
        ]);

        return request()->url() . "?" . http_build_query($params);
    }
}