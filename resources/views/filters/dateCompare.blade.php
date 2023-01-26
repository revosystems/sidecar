<div x-data="{ isOpen: false }" class="relative mx-2" >
    @include('sidecar::components.secondaryAction', [
        'action' => 'x-on:click=isOpen=!isOpen',
        'icon' => 'bar-chart',
        'label' => $compare->isComparing() ? $compare->getTitle() : __(config('sidecar.translationsPrefix').'compare')
    ])

    <div class="p-4 bg-white shadow-xl absolute z-50 mt-2" x-on:click.away="isOpen = false" x-cloak x-show.transition="isOpen">
        @include('sidecar::components.title', [
            'label' => __(config('sidecar.translationsPrefix').'dateRange'),
        ])
        <x-sidecar::select :id="'date-range-compare'" :name="'compare[period]'">
            @foreach(\Revo\Sidecar\Filters\DateHelpers::availableRanges() as $range => $period)
                <option value="{{$range}}"
                        @if($compare->period == $range) selected @endif
                        x-period-start="{{$period->start->toDateString()}}"
                        x-period-end="{{$period->end->toDateString()}}">
                    {{ __(config('sidecar.translationsPrefix').$range) }}
                </option>
            @endforeach
            <option value="custom" @if($compare->period == 'custom' || $compare->period === null) selected @endif>{{ __(config('sidecar.translationsPrefix').'custom') }} </option>
        </x-sidecar::select>
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
                @include('sidecar::components.mainAction', [
                    'tag' => 'a',
                    'id' => 'compare_date_button',
                    'icon' => 'bar-chart',
                    'label' => __(config('sidecar.translationsPrefix').'compare'),
                    'action' => "onclick=document.getElementById('shouldCompare').value='true';document.getElementById('sidecar-form').submit();",
                ])
            </div>
        </div>
    </div>
</div>
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
