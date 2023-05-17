<?php

namespace Revo\Sidecar\ExportFields;

class Link extends Text
{
    protected $linkIcon = null;

    public function linkIcon($icon) : self
    {
        $this->linkIcon = $icon;
        return $this;
    }

    public function getLinkTitle($row) : string {
        if ($icon = $this->linkIcon){
            return "<i class='fa fa-{$icon} fa-fw'></i>";
        }
        return $this->getValue($row);
    }

    public function toHtml($row) : string {
        if ($this->route == null) { return $this->getValue($row); }
        $link = route($this->route, $this->getValue($row));
        return "<a href='{$link}' class='{$this->linkClasses}' style='color:gray;'>{$this->getLinkTitle($row)}</a>";
    }
}