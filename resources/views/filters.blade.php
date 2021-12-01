<div class="m-4 p4 grid bg-broken-white b filters sidecar-filters">
    <form id="sidecar-form" action="">
        <input type="hidden" name="sort" value="{{request('sort')}}">
        <input type="hidden" name="sort_order" value="{{request('sort_order')}}">

        <div>
            @foreach($report->availableFilters()->sort() as $field)
                @includeWhen($field instanceof Revo\Sidecar\ExportFields\Date, 'sidecar::filters.date-new')
            @endforeach
            @if ($report->isComparable())
                @include('sidecar::filters.dateCompare')
            @endif
            @include('sidecar::filters.groupBy')
        </div>

        <div class="mt-4">
            <a class="button secondary dropdown">
                @icon(filter)
                {{ __(config('sidecar.translationsPrefix').'manageFilters') }}
            </a>
            <div class="dropdown-container p-4 mt-2">
                <div class="text-gray-400 uppercase mb-2">{{ __(config('sidecar.translationsPrefix').'filters') }}</div>
                <div class="">
                    @foreach($report->availableFilters()->sort() as $field)
                        @if (!($field instanceof Revo\Sidecar\ExportFields\Date))
                            <div class="mt-4"> @include('sidecar::filters.filter') </div>
                        @endif
                    @endforeach
                    <div class="text-right mt-4 -mr-4">
                        <button class="button secondary">
                            <i class="fa fa-filter fa-fw"></i>
                            {{ __(config('sidecar.translationsPrefix').'filter') }}
                        </button>
                    </div>
                </div>
            </div>
           @include('sidecar::filters.applied')
            <div class="mt-4">
                <button class="button primary">
{{--                    <i class="fa fa-filter fa-fw"></i>--}}
                    {{ __(config('sidecar.translationsPrefix').'apply') }}
                </button>
            </div>
        </div>
    </form>
</div>