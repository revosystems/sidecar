<?php

namespace Revo\Sidecar\MainActions;

use Revo\Sidecar\Report;

class MainAction
{
    public ?string $title;
    public ?string $icon;
    public ?string $url;

    public static function make(?string $title = null, ?string $icon = null, ?string $url = '')
    {
        $action = new static;
        $action->title = $title;
        $action->icon  = $icon;
        $action->url  = $url;
        return $action;
    }

    public function display(Report $report): string
    {
        return "<a class='h-7 py-1.5 px-2 mr-1 bg-white tet-xs border border-gray-300 rounded align-middle cursor-pointer shadow-sm sidecar-button-secondary relative' href='{$this->url}'> {$this->getIcon()} {$this->getTitle()} </a>";
    }

    protected function getIcon(): string
    {
        return $this->icon
            ? "<i class='fa fa-{$this->icon}'></i> "
            : '';
    }

    protected function getTitle(): string
    {
        return __(config('sidecar.translationsPrefix') . $this->title);
    }
}
