<div class="inline">
    @if($field->getIcon())
        <i class="fa fa-{{$field->getIcon()}} fa-fw"></i>
    @else
        {{ $field->getTitle() }}
    @endif

    @if ($field instanceof \Revo\Sidecar\ExportFields\Text)
        @include('sidecar::filters.search')
    @elseif ($field instanceof \Revo\Sidecar\ExportFields\Number)
        @include('sidecar::filters.number')
    @else
        @include('sidecar::filters.select')
    @endif
</div>
