<i class="fa fa-pie-chart fa-fw"></i>
<select name="groupBy">
    <option value="">--</option>
    @foreach($availableGroupings as $filter)
        @foreach($filter->groupings() as $grouping)
            <option value="{{$filter->getFilterField()}}:{{$grouping}}" @if(request('groupBy') == "{$filter->getFilterField()}:{$grouping}") selected @endif> {{ $filter->getTitle() }} ({{$grouping}})</option>
        @endforeach
    @endforeach
</select>