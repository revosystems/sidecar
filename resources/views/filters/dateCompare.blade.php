<x-ui::dropdown anchor="bottom-start" :offset="12">
    <x-slot name="trigger">
        <x-ui::secondary-button>
            <div class="flex space-x-2">
                <div>@icon(bar-chart)</div>
                <div class="hidden sm:block truncate">{{ $compare->isComparing() ? $compare->getTitle() : __(config('sidecar.translationsPrefix').'compare') }}</div>
            </div>
        </x-ui::secondary-button>
    </x-slot>

    @include('sidecar::components.title', [
       'label' => __(config('sidecar.translationsPrefix').'dateRange'),
   ])
    <x-ui::forms.select id="date-range-compare" name="compare[period]" class="min-w-64 w-full">
        @foreach(\Revo\Sidecar\Filters\DateHelpers::availableRanges() as $range => $period)
            <option value="{{$range}}"
                    @if($compare->period == $range) selected @endif
                    x-period-start="{{$period->start->toDateString()}}"
                    x-period-end="{{$period->end->toDateString()}}">
                {{ __(config('sidecar.translationsPrefix').$range) }}
            </option>
        @endforeach
        <option value="custom" @if($compare->period == 'custom' || $compare->period === null) selected @endif>{{ __(config('sidecar.translationsPrefix').'custom') }} </option>
    </x-ui::forms.select>
    <div class="grid">
        <div id="compare-custom-date-range" class="mt-4 @if($compare->period == 'custom' || $compare->period == null) @else hidden @endif">
            @include('sidecar::components.title', [
                'label' => __(config('sidecar.translationsPrefix').'custom'),
            ])
            <div class="flex flex-row space-x-2">
                @include('sidecar::components.input', [
                    'type' => 'date',
                    'width' => '145px',
                    'name' => 'compare[start]',
                    'id' => 'compare_start_date',
                    'value' => $compare->start,
                ])
                @include('sidecar::components.input', [
                    'type' => 'date',
                    'width' => '145px',
                    'name' => 'compare[end]',
                    'id' => 'compare_end_date',
                    'value' => $compare->end,
                ])
                <input id="shouldCompare" hidden name="shouldCompare" value="false">
            </div>
        </div>
        <div class="mt-6 text-right mb-1">
            <x-ui::primary-button
                    id="compare_date_button"
                      onclick="document.getElementById('shouldCompare').value='true';document.getElementById('sidecar-form').submit();"
                      :async="true"
            >
                @icon(bar-chart)
                {{ __(config('sidecar.translationsPrefix').'compare') }}
            </x-ui::primary-button>
        </div>
    </div>
</x-ui::dropdown>
@push(config('sidecar.scripts-stack'))
    <script>
        document.getElementById('date-range-compare').addEventListener('change', function(event){
            const optionSelected = this.options[this.selectedIndex]
            if (optionSelected.value == 'custom') {
                return document.getElementById('compare-custom-date-range').style.display = 'block'
            }
            document.getElementById('compare_start_date').value = optionSelected.getAttribute('x-period-start')
            document.getElementById('compare_end_date')  .value = optionSelected.getAttribute('x-period-end')
        })
    </script>
@endpush
