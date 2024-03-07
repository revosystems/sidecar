<div id="sidecar-applied-filters" class="mt-4 flex items-center space-x-1">
    @foreach($report->availableFilters()->sort() as $field)
        @if (\Illuminate\Support\Arr::dimensions($field->filterOptions()) > 1)
            @php ($options = collect($field->filterOptions())->mapWithKeys(fn($item) => $item))
        @else
            @php ($options = $field->filterOptions())
        @endif
        @foreach($options as $key => $value)
            @if($report->filters->isFilteringBy($field->getFilterField(), $key))
                <x-ui::tag>
                @if ($field instanceof \Revo\Sidecar\ExportFields\Number)
                    {{ $field->getTitle() }}
                    {{ $value }}
                    {{ $report->filters->filtersFor($field->getFilterField())['value'] }}
                @else
                    {{ $value }}
                @endif
                </x-ui::tag>
            @endif
        @endforeach
    @endforeach
</div>