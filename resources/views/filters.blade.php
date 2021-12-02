<div class="m-4 p4 grid bg-broken-white b filters sidecar-filters">
    <form id="sidecar-form" action="">
        <input type="hidden" name="sort" value="{{request('sort')}}">
        <input type="hidden" name="sort_order" value="{{request('sort_order')}}">

        <div class="inline">
            @foreach($report->availableFilters() as $field)
                @includeWhen($field instanceof Revo\Sidecar\ExportFields\Date, 'sidecar::filters.date-new')
            @endforeach
            @if ($report->isComparable())
                @include('sidecar::filters.dateCompare')
            @endif
            @include('sidecar::filters.groupBy')
        </div>
        @include('sidecar::filters.manage')
        @include('sidecar::filters.applied')
        <div class="mt-4">
            <button class="button primary">
{{--                    <i class="fa fa-filter fa-fw"></i>--}}
                {{ __(config('sidecar.translationsPrefix').'apply') }}
            </button>
        </div>
    </form>

</div>
