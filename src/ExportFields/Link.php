<?php

namespace Revo\Sidecar\ExportFields;

class Link extends Text
{
    public function getLinkTitle($row) : string {
        return $this->getValue($row);
    }

    public function toHtml($row) : string {
        if ($this->route == null) { return $this->getValue($row); }
        return link_to_route($this->route, $this->getLinkTitle($row), $this->getValue($row), ['class' => $this->linkClasses]);
    }
}