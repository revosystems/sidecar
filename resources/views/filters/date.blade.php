<div x-data="{ isOpen: false }" class="relative flex">
{{--    <div class="space-x-1">--}}
        <a class="button secondary small" onclick="shiftInterval(-1)"><</a>
        <a class="button secondary" x-on:click="isOpen = !isOpen">
            @icon({{$field->getIcon()}})
            {{ $report->filters->dateFilterTitleFor($field) }}
        </a>
        <div class="p-4 absolute z-50 mt-8 bg-white shadow-xl" x-on:click.away="isOpen = false" x-cloak x-show="isOpen" x-transition>
            <div class="text-gray-400 uppercase mb-2">{{ __(config('sidecar.translationsPrefix').'dateRange') }}</div>
            <select id=date-range-{{$field->getFilterField()}} name="dates[{{$field->getFilterField()}}][period]" style="width: 300px;">
                @foreach(\Revo\Sidecar\Filters\DateHelpers::availableRanges() as $range => $period)
                    <option value="{{$range}}"
                            @if($report->filters->datePeriodFilterFor($field) == $range) selected @endif
                            x-period-start="{{$period->start->toDateString()}}"
                            x-period-end="{{$period->end->toDateString()}}">
                        {{ __(config('sidecar.translationsPrefix').$range) }}
                    </option>
                @endforeach
                <option value="custom" @if($report->filters->datePeriodFilterFor($field) == 'custom' || $report->filters->datePeriodFilterFor($field) == null) selected @endif>{{ __('admin.custom') }} </option>
            </select>
            <div class="grid">
                <div id="custom-date-range" style="@if($report->filters->datePeriodFilterFor($field) == 'custom' || $report->filters->datePeriodFilterFor($field) == null) @else display:none; @endif">
                    <div class="text-gray-400 uppercase mb-2 mt-4">{{ __(config('sidecar.translationsPrefix').'custom') }}</div>
                    <div class="flex flex-row space-x-2">
                        <input type="date" id="start_date" style="width:145px"
                               name="dates[{{$field->getFilterField()}}][start]"
                               value="{{$report->filters->dateFilterStartFor($field)}}">

                        <input type="date" id="end_date" style="width:145px"
                               name="dates[{{$field->getFilterField()}}][end]"
                               value="{{$report->filters->dateFilterEndFor($field)}}">
                    </div>
                    <div class="mt-4 text-right">
                        <button id="filter_date_button" class="button">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                            {{ __('admin.filter') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <a class="button secondary small" onclick="shiftInterval(1)">></a>
{{--    </div>--}}
</div>
@push(config('sidecar.scripts-stack'))
    <script type='module'>
        document.getElementById('date-range-{{$field->getFilterField()}}').addEventListener('change', function(event){
            const optionSelected = this.options[this.selectedIndex]
            if (optionSelected.value == 'custom') {
                return document.getElementById('custom-date-range').style.display = 'block'
            }
            document.getElementById('start_date').value = optionSelected.attr('x-period-start')
            document.getElementById('end_date')  .value = optionSelected.attr('x-period-end')
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
