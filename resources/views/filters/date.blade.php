<div class="flex items-center space-x-1">
    <x-ui::secondary-button onclick="shiftInterval(-1)" :async="true">
        @icon(chevron-left)
    </x-ui::secondary-button>


    <x-ui::dropdown anchor="bottom-start" offset="12">
        <x-slot name="trigger">
            <x-ui::secondary-button>
                <div class="flex items-center space-x-2">
                    <div><x-ui::icon>{{ $field->getIcon() }}</x-ui::icon></div>
                    <div class="truncate">{{$report->filters->dateFilterTitleFor($field)}}</div>
                </div>
            </x-ui::secondary-button>
        </x-slot>

        @include('sidecar::components.title', [
            'label' => __(config('sidecar.translationsPrefix').'dateRange')
        ])
        <x-sidecar::select :id="'date-range-'.$field->getFilterField()" :name="'dates['.$field->getFilterField().'][period]'" class="min-w-64">
            @foreach(\Revo\Sidecar\Filters\DateHelpers::availableRanges() as $range => $period)
                <option value="{{$range}}"
                        @if($report->filters->datePeriodFilterFor($field) == $range) selected @endif
                        x-period-start="{{$period->start->toDateString()}}"
                        x-period-end="{{$period->end->toDateString()}}">
                    {{ __(config('sidecar.translationsPrefix').$range) }}
                </option>
            @endforeach
            <option value="custom" @if($report->filters->datePeriodFilterFor($field) == 'custom' || $report->filters->datePeriodFilterFor($field) === null) selected @endif>{{ __(config('sidecar.translationsPrefix').'custom') }} </option>
        </x-sidecar::select>
        <div class="grid">
            <div id="custom-date-range" class="mt-4" style="@if($report->filters->datePeriodFilterFor($field) == 'custom' || $report->filters->datePeriodFilterFor($field) == null) @else display:none; @endif">
                @include('sidecar::components.title', [
                    'label' => __(config('sidecar.translationsPrefix').'dateRange'),
                ])
                <div class="flex flex-row space-x-2">
                    @include('sidecar::components.input', [
                        'id' => 'start_date',
                        'type' => 'date',
                        'width' => '145px',
                        'name' => "dates[{$field->getFilterField()}][start]",
                        'value' => $report->filters->dateFilterStartFor($field),
                    ])
                    @include('sidecar::components.input', [
                        'id' => 'end_date',
                        'type' => 'date',
                        'width' => '145px',
                        'name' => "dates[{$field->getFilterField()}][end]",
                        'value' => $report->filters->dateFilterEndFor($field),
                    ])
                </div>
                <div class="mt-4 text-right">
                    @include('sidecar::components.mainAction', [
                        'id' => 'filter_date_button',
                        'icon' => 'filter',
                        'label' => __(config('sidecar.translationsPrefix').'filter'),
                    ])
                </div>
            </div>
        </div>
    </x-ui::dropdown>


    <x-ui::secondary-button onclick="shiftInterval(1)" :async="true">
        @icon(chevron-right)
    </x-ui::secondary-button>
</div>

@push(config('sidecar.scripts-stack'))
    <script>
        document.getElementById('date-range-{{$field->getFilterField()}}').addEventListener('change', function(event){
            const optionSelected = this.options[this.selectedIndex]
            if (optionSelected.value == 'custom') {
                return document.getElementById('custom-date-range').style.display = 'block'
            }
            document.getElementById('start_date').value = optionSelected.getAttribute('x-period-start')
            document.getElementById('end_date')  .value = optionSelected.getAttribute('x-period-end')
            document.getElementById('filter_date_button').click()
        });

        Date.prototype.addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        };

        function formatDate(date) {
            var dd = date.getDate();
            var mm = date.getMonth() + 1; //January is 0!
            var yyyy = date.getFullYear();
            if (dd<10) {
                dd = '0' + dd;
            }
            if (mm<10) {
                mm = '0' + mm;
            }
            return yyyy + '-' + mm + '-' + dd;
        }

        function shiftInterval(sign) {
            var start       = new Date('{{ $report->filters->dateFilterStartFor($field) }}');
            var end         = new Date('{{ $report->filters->dateFilterEndFor($field) }}');
            const millisecondsForDay = 3600*24*1000;
            var interval    = (end - start) / millisecondsForDay;
            start   = start.addDays(interval ? interval*sign : sign);
            end     = end.addDays(interval ? interval*sign : sign);
            document.getElementById('date-range-{{$field->getFilterField()}}').value = 'custom'
            document.getElementById('start_date').value = formatDate(start)
            document.getElementById('end_date')  .value = formatDate(end)
            document.getElementById('filter_date_button').click();
        }
    </script>
@endpush
