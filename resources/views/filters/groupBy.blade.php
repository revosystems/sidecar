@if ($report->availableGroupings()->count() > 0)
    <div class="">
{{--        <i class="fa fa-pie-chart fa-fw text-gray-400"></i>--}}
{{--        {{ __(config('sidecar.translationsPrefix').'groupBy') }}--}}
        <select id="sidecar-groupby" name="groupBy[]" multiple class="p-1" style="width: 150px">
            @foreach($report->availableGroupings() as $filter)
                @foreach($filter->groupings() as $grouping)
                    <option value="{{$filter->getFilterField()}}:{{$grouping}}"
                        @if ($report->filters->groupBy->isGroupingBy($filter->getFilterField(), $grouping)) selected @endif>
                        @if ($grouping == "default")
                            {{ str_replace(" (default)", "", "{$filter->getTitle()}") }}
                        @else
                            @php $groupingTranslated = __c(config('sidecar.translationsPrefix').$grouping); @endphp
                            {{ str_replace(" (default)", "", "{$filter->getTitle()} ({$groupingTranslated})") }}
                        @endif
                    </option>
                @endforeach
            @endforeach
        </select>
    </div>
@endif

@push(config('sidecar.scripts-stack'))
    <script>
        $('#sidecar-groupby').select2({
            placeholder: "{{__('admin.groupBy') }}...",
            allowClear: true
        })
        $('#sidecar-groupby').change(function(){
            $('#sidecar-apply-button').show('fast');
            $('#sidecar-apply-button').removeClass('hidden');
        });
    </script>
@endpush