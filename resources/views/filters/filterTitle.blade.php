@if($tooltip = $field->getFilterTooltip())
    <x-ui::tooltip>
        <x-slot name="trigger">
            @if($field->getIcon())
                <x-ui::icon>{{$field->getIcon()}}</x-ui::icon>
            @else
                {{ $field->getTitle() }}
            @endif
        </x-slot>
        {{ $tooltip }}
    </x-ui::tooltip>

@else
    @if($field->getIcon())
        <x-ui::icon>{{$field->getIcon()}}</x-ui::icon>
    @else
        {{ $field->getTitle() }}
    @endif
@endif