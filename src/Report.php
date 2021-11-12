<?php

namespace Revo\Sidecar;

// [ ] Autodiscovery
// [ ] Config => layout
// [ ] Config => Reports path
// [ ] Autodiscovery recursive
class Report
{
    protected $model;
    protected $with = [];

    public function query(){
        return $this->model::with($this->with);
    }

    public function paginate($pagination = 25)
    {
        return $this->query()->select($this->select)->paginate($pagination);
    }
}