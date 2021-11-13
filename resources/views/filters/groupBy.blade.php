<i class="fa fa-pie-chart fa-fw"></i>
<select name="groupBy">
    <option value="">--</option>
    @foreach($availableGroupings as $filter)
        @foreach($filter->groupings() as $grouping)
            <option value="{{$filter->getSelectField()}}:{{$grouping}}" @if(request('groupBy') == "{$filter->getSelectField()}:{$grouping}") selected @endif> {{ $filter->getTitle() }} ({{$grouping}})</option>
        @endforeach
    @endforeach
</select>