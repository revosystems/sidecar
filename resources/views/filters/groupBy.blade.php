<i class="fa fa-pie-chart fa-fw"></i>
<select name="groupBy[]" multiple>
    <option value="">--</option>
    @foreach($report->availableGroupings() as $filter)
        @foreach($filter->groupings() as $grouping)
            <option value="{{$filter->getFilterField()}}:{{$grouping}}"
                    @if ($report->filters->groupBy->isGroupingBy($filter->getFilterField(), $grouping)) selected @endif
            >
                {{ $filter->getTitle() }} ({{$grouping}})
            </option>
        @endforeach
    @endforeach
</select>