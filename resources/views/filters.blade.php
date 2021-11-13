<div class="m-4">
    <form action="">

        @foreach($availableFilters as $filter)
            {{ $filter->getTitle() }}
            @if ($filter instanceof Revo\Sidecar\ExportFields\Date)
                @include('sidecar::filters.date')
            @else
                @include('sidecar::filters.select')
            @endif
        @endforeach
        <button>{{ __(config('sidecar.translationsPrefix').'.filter') }}</button>
    </form>
</div>