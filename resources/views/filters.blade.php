<x-ui::card class="m-4 px-2 py-4 grid bg-broken-white  filters sidecar-filters">
    <form id="sidecar-form" action="">
        <input type="hidden" name="sort" value="{{request('sort')}}">
        <input type="hidden" name="sort_order" value="{{request('sort_order')}}">

        <div class="flex flex-col gap-2 md:space-y-0 md:flex-row md:items-center">
            @if ($report->availableFilters()->contains(fn ($filter) => $filter instanceof Revo\Sidecar\ExportFields\Date) || $report->isComparable())
                <div class="flex flex-row justify-left items-center gap-2">
                    @foreach($report->availableFilters() as $field)
                        @includeWhen($field instanceof Revo\Sidecar\ExportFields\Date, 'sidecar::filters.date')
                    @endforeach
                    
                    @includeWhen($report->isComparable(), 'sidecar::filters.dateCompare')
                </div>
            @endif
            <div class="flex flex-col md:flex-row w-full md:w-auto justify-left items-center gap-2 md:grow">
                <div class="grow w-full">
                @include('sidecar::filters.groupBy')
                </div>
                @include('sidecar::filters.manage')
            </div>
        </div>

        @include('sidecar::filters.applied')

        <div id='sidecar-apply-button' class="mt-4 hidden">
            @include('sidecar::components.mainAction', [
                'label' => __(config('sidecar.translationsPrefix').'apply')
            ])
        </div>
    </form>

</x-ui::card>
