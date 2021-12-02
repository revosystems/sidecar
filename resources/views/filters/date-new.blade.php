<a class="secondary button" onclick="shiftInterval(-1)"><</a>
<div x-data="{ isOpen: false }" class="inline">
    <a class="button secondary" @click="isOpen = !isOpen">
        @icon(calendar)
        {{ $report->filters->dateFilterTitleFor($field) }}
    </a>
    <div class="ml-4 p-4 absolute bg-white shadow-xl" @click.outside="isOpen = false" x-cloak x-show.transition="isOpen">
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
            <div id="custom-date-range" class="  @if($report->filters->datePeriodFilterFor($field) == 'custom' || $report->filters->datePeriodFilterFor($field) == null) @else hidden @endif">
                <div class="text-gray-400 uppercase mb-2 mt-4">{{ __(config('sidecar.translationsPrefix').'custom') }}</div>
                @icon(calendar)
                <input type="date" id="start_date"
                       name="dates[{{$field->getFilterField()}}][start]"
                       value="{{$report->filters->dateFilterStartFor($field)}}">

                <input type="date" id="end_date"
                       name="dates[{{$field->getFilterField()}}][end]"
                       value="{{$report->filters->dateFilterEndFor($field)}}">

                <div class="mt-4 text-right">
                    <button id="filter_date_button" class="button">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                        {{ __('admin.filter') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="secondary button" onclick="shiftInterval(1)">></a>

@push(config('sidecar.scripts-stack'))
    <script>
        $('#date-range-{{$field->getFilterField()}}').change(function(event){
            var optionSelected = $(this).find('option:selected')
            if (optionSelected.val() == 'custom') {
                return $('#custom-date-range').show('fast')
            }
            $('#start_date').val(optionSelected.attr('x-period-start') );
            $('#end_date')  .val(optionSelected.attr('x-period-end') );
            $('#filter_date_button').click();
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
            $('#date-range-{{$field->getFilterField()}}').val('custom')
            $('#start_date').val( formatDate(start) );
            $('#end_date')  .val( formatDate(end) );
            $('#filter_date_button').click();
        }
    </script>
@endpush