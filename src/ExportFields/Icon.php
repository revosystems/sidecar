<?php

namespace Revo\Sidecar\ExportFields;

class Icon extends ExportField
{
    public function toHtml($row) : string {
        return '<i class="fa fa-' . $this->getValue($row) .'" aria-hidden="true"></i>';
    }

}