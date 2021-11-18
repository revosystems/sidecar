<a class="secondary button" onclick="shiftInterval(-1)"><</a>
<a class="button secondary dropdown">
    @icon(calendar)
    {{ Carbon\Carbon::parse($report->filters->dateFilterStartFor($field))->format("jS F Y") }} -
    {{ Carbon\Carbon::parse($report->filters->dateFilterEndFor($field))->format("jS F Y") }}
</a>
<div class="dropdown-container ml5">
    <div class="grid">
        <div class="p3">
            <ul class="mt-2">
                <li class="pb1"><a class="pointer" onclick="filterSetToday()">{{ __('admin.today') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="filterSetYesterday()">{{ __('admin.yesterday') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="filterSetThisWeek()">{{ __('admin.thisWeek') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="filterSetThisMonth()">{{ __('admin.thisMonth') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="filterSetLastDays(30)">{{ __('admin.last30days') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="filterSetLastDays(60)">{{ __('admin.last60days') }}</a></li>
                <li class="pb3"><a class="pointer" onclick="filterSetLastDays(90)">{{ __('admin.last90days') }}</a></li>
                <li class=""><a class="button secondary pointer" onclick="$('#custom-date-range').show('fast')">{{ __('admin.customRange') }}</a></li>
            </ul>
        </div>
        <div id="custom-date-range" class="pl3 pt1 hidden">
            @icon(calendar)
            <input type="date" name="dates[{{$field->getSelectField()}}][start]"
                   id="start_date"
                   value="{{$report->filters->dateFilterStartFor($field)}}">

            <input type="date" name="dates[{{$field->getSelectField()}}][end]"
                   id="end_date"
                   value="{{$report->filters->dateFilterEndFor($field)}}">
{{--            {{ Form::input('date', 'start_date', $field->filterStart(), ["id" => "start_date"]) }}--}}
{{--            {{ Form::input('date', 'end_date',   $field->filterEnd(), ["id" => "end_date"]) }}--}}
            <div class="mt3 text-right">
                <button id="filter_date_button" class="button">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                    {{ __('admin.filter') }}
                </button>
            </div>
        </div>
    </div>
</div>
<a class="secondary button" onclick="shiftInterval(1)">></a>

<script>
    function filterSetToday(){
        var today = moment().format('YYYY-MM-DD');
        $('#start_date').val( today );
        $('#end_date')  .val( today );
        $('#filter_date_button').click();
    }

    function filterSetYesterday() {
        var yesterday   = moment().subtract(1, 'days').format('YYYY-MM-DD');
        $('#start_date').val( yesterday );
        $('#end_date')  .val( yesterday );
        $('#filter_date_button').click();
    }

    function filterSetThisWeek() {
        var start   = moment().startOf('week').add(1,'days').format('YYYY-MM-DD');
        var end     = moment().endOf('week').add(1,'days').format('YYYY-MM-DD');
        $('#start_date').val( start );
        $('#end_date')  .val( end );
        $('#filter_date_button').click();
    }

    function filterSetThisMonth() {
        var start   = moment().startOf('month').format('YYYY-MM-DD');
        var end     = moment().endOf('month').format('YYYY-MM-DD');
        $('#start_date').val( start );
        $('#end_date')  .val( end );
        $('#filter_date_button').click();
    }

    function filterSetLastDays( days ) {
        var start   = moment().subtract(days, 'days').format('YYYY-MM-DD');
        var end     = moment().format('YYYY-MM-DD');
        $('#start_date').val( start );
        $('#end_date')  .val( end );
        $('#filter_date_button').click();
    }

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
        $('#start_date').val( formatDate(start) );
        $('#end_date')  .val( formatDate(end) );
        $('#filter_date_button').click();
    }
</script>