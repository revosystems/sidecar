<?php

namespace Revo\Sidecar;

class Sidecar
{

    static public function make($name) : ?Report
    {
        $path = "\\App\\Reports\\V2\\{$name}";
        return new $path;
    }

}