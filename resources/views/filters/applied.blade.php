@php($fields = [])
@foreach($report->availableFilters() as $field)
    @php($filterField = $field->getFilterField())
    @if($report->filters->isFilteringBy($filterField))
        @php($fields[] = $field)
    @endif
@endforeach

@if(count($fields) > 0)
    <div id="sidecar-applied-filters" class="mt-4 flex flex-wrap items-center gap-1">
        @foreach($fields as $field)
            @if ($field instanceof \Revo\Sidecar\ExportFields\Boolean)
                @include('sidecar::filters.applied.boolean')
            @elseif ($field instanceof \Revo\Sidecar\ExportFields\Number)
                @include('sidecar::filters.applied.number')
            @elseif ($field instanceof \Revo\Sidecar\ExportFields\Text)
                @include('sidecar::filters.applied.text')
            @elseif ($field instanceof \Revo\Sidecar\ExportFields\DateTime)
                @include('sidecar::filters.applied.time')
            @else
                @include('sidecar::filters.applied.select')
            @endif
        @endforeach
        <a href="{{ url(request()->getPathInfo()) }}">
            <x-ui::tertiary-button icon="xmark">
                {{__(config('sidecar.translationsPrefix').'clearAll')}}
            </x-ui::tertiary-button>
        </a>
    </div>
@endif
