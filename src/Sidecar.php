<?php

namespace Revo\Sidecar;

class Sidecar
{

    static public function make($name)
    {
        $path = "\\App\\Reports\\V2\\{$name}";
        return new $path;
    }

}