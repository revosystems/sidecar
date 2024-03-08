<div id="sidecar-applied-filters" class="mt-4 flex flex-wrap items-center gap-1">
    @php($count = 0)
    @foreach($report->availableFilters() as $field)
        @if($report->filters->isFilteringBy($field->getFilterField()))
            @php($count++)
            @if ($field instanceof \Revo\Sidecar\ExportFields\Number)
                @include('sidecar::filters.applied.number')
            @elseif ($field instanceof \Revo\Sidecar\ExportFields\Text)
                @include('sidecar::filters.applied.text')
            @elseif ($field instanceof \Revo\Sidecar\ExportFields\DateTime)

            @else
                @include('sidecar::filters.applied.select')
            @endif
        @endif
    @endforeach
    @if($count > 0)
            <a href="{{ url(request()->getPathInfo()) }}">
                    <x-ui::tertiary-button icon="xmark">
                        {{__(config('sidecar.translationsPrefix').'clearAll')}}
                    </x-ui::tertiary-button>
            </a>
    @endif
</div>