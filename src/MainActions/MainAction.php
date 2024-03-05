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
        return view('sidecar::components.secondaryAction',[
            'title' => $this->title,
            'icon' => $this->icon,
            'url' => $this->url
        ])->render();
    }

    protected function getIcon(): string
    {
        return $this->icon;
    }

    protected function getTitle(): string
    {
        return $this->title;
    }
}
