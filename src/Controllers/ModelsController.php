<?php

namespace Revo\Sidecar\Controllers;

use App\Models\Menu\Favourite;
use App\Models\Menu\MenuItem;

class ModelsController
{
    public function search($model, $field = 'name'){
        return (request('search') != '' ? $model::where($field,'like',"%" . request("search") . "%") : $model::query())
            ->limit(100)
            ->pluck($field, 'id')->map(function($value, $key){
                return ["id" => $key, "name" => $value];
            });
    }
}