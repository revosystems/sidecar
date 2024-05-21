<?php

namespace Revo\Sidecar\ExportFields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Revo\Sidecar\Filters\Filters;

class Boolean extends Number {

    public function toHtml($row): string
    {
        return view('ui::components.active', [
            'attributes' => new ComponentAttributeBag(),
            'active' => $this->getValue($row)
        ])->render();
    }

    public function toCsv($row)
    {
        return $this->getValue($row) ? "true" : "false";
    }

    public function applyFilter(Filters $filters, Builder $query, $key, $values): Builder
    {

        if ($values == null) { return $query; }
        return $query->where($key, $values);
    }

}