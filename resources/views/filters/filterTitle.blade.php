@if($tooltip = $field->getFilterTooltip())
    <div class='has-tooltip cursor max-w-sm'>
        <span class='tooltip rounded shadow-lg p-2 text-xs bg-black text-white mt-7'> {{ $tooltip }} </span>
        @if($field->getIcon())
            <i class="fa fa-{{$field->getIcon()}} fa-fw mt-2"></i>
        @else
            {{ $field->getTitle() }}
        @endif
    </div>
@else
    @if($field->getIcon())
        <i class="fa fa-{{$field->getIcon()}} fa-fw mt-2"></i>
    @else
        {{ $field->getTitle() }}
    @endif
@endif