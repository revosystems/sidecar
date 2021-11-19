<?php

namespace Revo\Sidecar\ExportFields;

class Link extends ExportField
{
    public ?string $route = null;
    protected ?string $linkClasses = "";

    public function route($route, $classes = null) : self {
        $this->route = $route;
        $this->linkClasses = $classes;
        return $this;
    }

    public function linkClasses($classes) : self{
        $this->linkClasses = $classes;
        return $this;
    }

    public function getLinkTitle($row) : string {
        return $this->getValue($row);
    }

    public function toHtml($row) : string {
        if ($this->route == null) { return $this->getValue($row); }
        return link_to_route($this->route, $this->getLinkTitle($row), $this->getValue($row), ['class' => $this->linkClasses]);
    }

}