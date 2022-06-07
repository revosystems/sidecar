<?php

namespace Revo\Sidecar\MainActions;

use Revo\Sidecar\Report;

class MainAction
{
    public function __construct(public ?string $title = null, public ?string $icon = null, public ?string $url = '') {}

    public static function make(?string $title = null, ?string $icon = null, ?string $url = '')
    {
        return new static($title, $icon, $url);
    }

    public function display(Report $report): string
    {
        return "<a class='button secondary relative' href='{$this->url}'> {$this->getIcon()} {$this->getTitle()} </a>";
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