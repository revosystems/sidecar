<div x-data="{ isOpen: false }" class="relative" >
    <a class="secondary button" @click="isOpen = !isOpen">
        <i class="fa fa-bar-chart" aria-hidden="true"></i>
        @if($compare->isComparing())
            {{ $compare->getTitle() }}
        @else
            {{ __(config('sidecar.translationsPrefix').'compare') }}
        @endif
    </a>

    <div class="p-4 bg-white shadow-xl absolute z-50 mt-2" @click.outside="isOpen = false" x-cloak x-show.transition="isOpen">
        <div class="text-gray-400 uppercase mb-2">{{ __(config('sidecar.translationsPrefix').'dateRange') }}</div>
        <select id=date-range-compare name="compare[period]" style="width: 300px;">
            @foreach(\Revo\Sidecar\Filters\DateHelpers::availableRanges() as $range => $period)
                <option value="{{$range}}"
                        @if($compare->period == $range) selected @endif
                        x-period-start="{{$period->start->toDateString()}}"
                        x-period-end="{{$period->end->toDateString()}}">
                    {{ __(config('sidecar.translationsPrefix').$range) }}
                </option>
            @endforeach
            <option value="custom" @if($compare->period == 'custom' || $compare->period == null) selected @endif>{{ __('admin.custom') }} </option>
        </select>
        <div class="grid">
            <div id="compare-custom-date-range" class="@if($compare->period == 'custom' || $compare->period == null) @else hidden @endif">
                <div class="text-gray-400 uppercase mb-2 mt-4">{{ __(config('sidecar.translationsPrefix').'custom') }}</div>
                <div class="flex flex-row space-x-2">
                    <input style="width:145px" type="date" name="compare[start]" id="compare_start_date" value="{{$compare->start}}">
                    <input style="width:145px" type="date" name="compare[end]"   id="compare_end_date"   value="{{$compare->end}}">
                    <input id="shouldCompare" hidden name="shouldCompare" value="false">
                </div>

                <div class="mt-6 text-right mb-1">
                    <a id="compare_date_button" class="button p-2" onclick="$('#shouldCompare').val('true'); $('#sidecar-form').submit(); ">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                        {{ __('admin.compare') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{{--<a class="secondary button" onclick="shiftInterval(1)"></a>--}}
@push(config('sidecar.scripts-stack'))
    <script>
        $('#date-range-compare').change(function(event){
            console.log("here");
            var optionSelected = $(this).find('option:selected')
            if (optionSelected.val() == 'custom') {
                return $('#compare-custom-date-range').show('fast')
            }
            $('#compare_start_date').val(optionSelected.attr('x-period-start') );
            $('#compare_end_date')  .val(optionSelected.attr('x-period-end') );
            //$('#compare_date_button').click();
        });
    </script>
@endpush