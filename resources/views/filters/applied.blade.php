<div id="sidecar-applied-filters" class="mt-4">
    @foreach($report->availableFilters()->sort() as $field)
        @foreach($field->filterOptions() as $key => $value)
            @if($report->filters->isFilteringBy($field->getFilterField(), $key))
                <div class="inline bg-gray-200 text-xs text-gray-600 rounded p-1 mr-1">
                {{ $value }}
                @if ($field instanceof \Revo\Sidecar\ExportFields\Number)
                    {{ $report->filters->filtersFor($field->getFilterField())['value'] }}
                @endif
                </div>
            @endif
        @endforeach
    @endforeach
</div>