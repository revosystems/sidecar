<div id="sidecar-applied-filters" class="mt-4 flex flex-wrap items-center gap-1">
    @foreach($report->availableFilters() as $field)
        @if($report->filters->isFilteringBy($field->getFilterField()))
            @if ($field instanceof \Revo\Sidecar\ExportFields\Number)
                @include('sidecar::filters.applied.number')
            @elseif ($field instanceof \Revo\Sidecar\ExportFields\Text)
                @include('sidecar::filters.applied.text')
            @elseif ($field instanceof \Revo\Sidecar\ExportFields\DateTime)
                hola
            @else
                @include('sidecar::filters.applied.select')
            @endif
        @endif
    @endforeach
</div>