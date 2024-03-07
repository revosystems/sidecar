<?php

namespace Revo\Sidecar\Controllers;

use App\Models\Menu\Favourite;
use App\Models\Menu\MenuItem;

class ModelsController
{
    public function search($model, $field = 'name'){
        return $this->filterIds($this->baseQuery($model, $field))
            ->limit(100)->get()->map(function($object){
                return ["id" => $object->id, "name" => $object->name];
            });
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