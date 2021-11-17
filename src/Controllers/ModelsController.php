<?php

namespace Revo\Sidecar\Controllers;

use App\Models\Menu\Favourite;
use App\Models\Menu\MenuItem;

class ModelsController
{
    public function search($model, $field = 'name'){
        return $model::where($field,'like',"%" . request("search") . "%")
            ->limit(150)
            ->pluck($field, 'id')->map(function($value, $key){
                return ["id" => $key, "name" => $value];
            });
    }
}