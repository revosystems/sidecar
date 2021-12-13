<td style="padding-left:0px">
    @if($field->getIcon())
        <i class="fa fa-{{$field->getIcon()}} fa-fw mt-2"></i>
    @else
        {{ $field->getTitle() }}
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
