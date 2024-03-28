<div>
<div x-on:click="selectedFilter='{{$field->getFilterField()}}'" x-show="selectedFilter==null">
    <div class="flex gap-2 items-center hover:bg-neutral-50 py-2 px-1 rounded cursor-pointer transition-all justify-between">
        <div>
            @include('sidecar::filters.filterTitle')
        </div>

{{--        @php ( var_dump($report->filters->filtersFor($field->getFilterField())) )--}}
        @if($count = $report->filters->filtersFor($field->getFilterField())->count())
            <div class="rounded bg-neutral-100 text-gray-400 w-6 text-center text-xs">
                {{ $count }}
            </div>
        @endif
    </div>
</div>
    <div x-show="selectedFilter== '{{$field->getFilterField()}}'">

        <div class="flex gap-2 items-center mb-2">
            <div x-on:click="selectedFilter=null" class="cursor-pointer hover:bg-neutral-100 rounded p-2 transition-all">
                @icon(arrow-left)
            </div>
            @include('sidecar::filters.filterTitle')
        </div>


        @if ($field instanceof \Revo\Sidecar\ExportFields\Text)
            @include('sidecar::filters.search')
        @elseif ($field instanceof \Revo\Sidecar\ExportFields\Boolean)
            @include('sidecar::filters.boolean')
        @elseif ($field instanceof \Revo\Sidecar\ExportFields\Number)
            @include('sidecar::filters.number')
        @elseif ($field instanceof \Revo\Sidecar\ExportFields\Date)
            @include('sidecar::filters.time')
        @else
            @include('sidecar::filters.select')
        @endif
    </div>
</div>