@if ($report->availableGroupings()->count() > 0)
    <div class="inline">
        <i class="fa fa-pie-chart fa-fw"></i>
        <select name="groupBy[]" multiple>
            <option value="">--</option>
            @foreach($report->availableGroupings() as $filter)
                @foreach($filter->groupings() as $grouping)
                    <option value="{{$filter->getFilterField()}}:{{$grouping}}"
                            @if ($report->filters->groupBy->isGroupingBy($filter->getFilterField(), $grouping)) selected @endif
                    >
                        {{ str_replace(" (default)", "", "{$filter->getTitle()} ({$grouping})") }}
                    </option>
                @endforeach
            @endforeach
        </select>
    </div>
@endif