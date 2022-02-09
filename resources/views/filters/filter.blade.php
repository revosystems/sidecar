<td style="padding-left:0px">
@if($tooltip = $field->getTooltip())
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
</td>
<td>
    @if ($field instanceof \Revo\Sidecar\ExportFields\Text)
        @include('sidecar::filters.search')
    @elseif ($field instanceof \Revo\Sidecar\ExportFields\Number)
        @include('sidecar::filters.number')
    @elseif ($field instanceof \Revo\Sidecar\ExportFields\Date)
        @include('sidecar::filters.time')
    @else
        @include('sidecar::filters.select')
    @endif
</td>
