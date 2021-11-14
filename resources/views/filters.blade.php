<div class="m-4 p4 grid bg-broken-white b filters sidecar-filters">
    <form action="">
        @foreach($availableFilters->sort() as $filter)
            @if ($filter instanceof Revo\Sidecar\ExportFields\Date)
                @include('sidecar::filters.date')
            @else
                @include('sidecar::filters.select')
            @endif
        @endforeach

        @include('sidecar::filters.groupBy')

        <br><br>
        <button class="button primary">
            <i class="fa fa-filter fa-fw"></i>
            {{ __(config('sidecar.translationsPrefix').'filter') }}
        </button>
    </form>
</div>