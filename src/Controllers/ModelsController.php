<?php

namespace Revo\Sidecar\Controllers;

use App\Models\Menu\Favourite;
use App\Models\Menu\MenuItem;

class ModelsController
{
    public function search($model, $field = 'name'){
        return [
            1 => "hola",
            2 => "que",
            3 => "tal",
        ];
        return $this->filterIds($this->baseQuery($model, $field))
            ->limit(100)
            ->pluck($field, 'id');
    }

    private function baseQuery($model, $field) {
        return (request('search') != '' ? $model::where($field,'like',"%" . request("search") . "%") : $model::query());
    }

    private function filterIds($query) {
        $ids = request('onlyIds');
        if (!$ids) { return $query; }
        return $query->whereIn('id', $ids);
    }
}