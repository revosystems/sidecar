<div class="m-4 p4 grid bg-broken-white b filters sidecar-filters">
    <form id="sidecar-form" action="">
        <input type="hidden" name="sort" value="{{request('sort')}}">
        <input type="hidden" name="sort_order" value="{{request('sort_order')}}">

        <div class="flex flex-row justify-left items-center space-x-2">
            @foreach($report->availableFilters() as $field)
                @includeWhen($field instanceof Revo\Sidecar\ExportFields\Date, 'sidecar::filters.date')
            @endforeach
            @if ($report->isComparable())
                @include('sidecar::filters.dateCompare')
            @endif
            @include('sidecar::filters.groupBy')
            @include('sidecar::filters.manage')
        </div>
        @include('sidecar::filters.applied')
        <div id='sidecar-apply-button' class="mt-4 hidden">
            @include('sidecar::components.mainAction', [
                'label' => __(config('sidecar.translationsPrefix').'apply')
            ])
        </div>
    </form>

</div>
