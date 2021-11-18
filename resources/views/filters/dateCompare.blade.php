<a class="secondary button dropdown">
    <i class="fa fa-bar-chart" aria-hidden="true"></i>
    @if($compare->isComparing())
            {{ Carbon\Carbon::parse($compare->start)->format("jS F Y") }} -
            {{ Carbon\Carbon::parse($compare->end)->format("jS F Y") }}
    @else
        {{ __(config('sidecar.translationsPrefix').'compare') }}
    @endif
</a>

<div class="dropdown-container ml5" style="margin-left:350px">
    <div class="grid">
        <div class="p3">
            <ul class="mt-2">
                <li class="pb1"><a class="pointer" onclick="compareSetToday()">{{ __('admin.today') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="compareSetYesterday()">{{ __('admin.yesterday') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="compareSetThisWeek()">{{ __('admin.thisWeek') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="compareSetThisMonth()">{{ __('admin.thisMonth') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="compareSetLastDays(30)">{{ __('admin.last30days') }}</a></li>
                <li class="pb1"><a class="pointer" onclick="compareSetLastDays(60)">{{ __('admin.last60days') }}</a></li>
                <li class="pb3"><a class="pointer" onclick="compareSetLastDays(90)">{{ __('admin.last90days') }}</a></li>
            </ul>
        </div>
        <div id="compare-custom-date-range" class="pl3 pt1">
            @icon(calendar)
            <input type="date" name="compare[start]" id="compare_start_date" value="{{$compare->start}}">
            <input type="date" name="compare[end]"   id="compare_end_date"   value="{{$compare->end}}">
            <input id="shouldCompare" hidden name="shouldCompare" value="false">

            <div class="mt3 text-right">
                <a id="filter_date_button" class="button" onclick="$('#shouldCompare').val('true'); $('#sidecar-form').submit(); ">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    {{ __('admin.compare') }}
                </a>
            </div>
        </div>
    </div>
</div>
{{--<a class="secondary button" onclick="shiftInterval(1)"></a>--}}
<script>
    function compareSetToday(){
        var today = moment().format('YYYY-MM-DD');
        $('#compare_start_date').val( today );
        $('#compare_end_date')  .val( today );
    }

    function compareSetYesterday() {
        var yesterday   = moment().subtract(1, 'days').format('YYYY-MM-DD');
        $('#compare_start_date').val( yesterday );
        $('#compare_end_date')  .val( yesterday );
    }

    function compareSetThisWeek() {
        var start   = moment().startOf('week').add(1,'days').format('YYYY-MM-DD');
        var end     = moment().endOf('week').add(1,'days').format('YYYY-MM-DD');
        $('#compare_start_date').val( start );
        $('#compare_end_date')  .val( end );
    }

    function compareSetThisMonth() {
        var start   = moment().startOf('month').format('YYYY-MM-DD');
        var end     = moment().endOf('month').format('YYYY-MM-DD');
        $('#compare_start_date').val( start );
        $('#compare_end_date')  .val( end );
    }

    function compareSetLastDays( days ) {
        var start   = moment().subtract(days, 'days').format('YYYY-MM-DD');
        var end     = moment().format('YYYY-MM-DD');
        $('#compare_start_date').val( start );
        $('#compare_end_date')  .val( end );
    }
</script>