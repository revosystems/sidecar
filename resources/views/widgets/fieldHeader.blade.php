<div class='cursor max-w-sm'>
    @if ($field->sortable)
        <x-ui::sort-header
                :active="$report->filters->sort->field == $field->getFilterField()"
                direction="{{$report->filters->sort->order}}"
                :sortDescLink=" \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'desc') "
                :sortAscLink=" \Revo\Sidecar\Filters\Sort::queryUrlFor($field, 'asc') "
                :tooltip="$field->getTooltip()"
        >
            @if (!$field->hideTitle) {{ $field->getTitle() }} @endif
        </x-ui::sort-header>
    @else
        <x-ui::tooltip :enabled="$field->getTooltip()!== null">
            <x-slot name="trigger">
                <span @class([
                    "underline decoration-dotted" => ($field->getTooltip()!== null)
                ])>
                    @if (!$field->hideTitle) {{ $field->getTitle() }} @endif
                </span>
            </x-slot>
            {{ $field->getTooltip() }}
        </x-ui::tooltip>
    @endif
</div>