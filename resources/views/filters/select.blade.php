@if($filter->getIcon())
    <i class="fa fa-{{$filter->getIcon()}} fa-fw"></i>
@else
    {{ $filter->getTitle() }}
@endif

<select name="filters[{{$filter->getFilterField()}}][]" multiple>
    <option value="">--</option>
    @foreach($filter->filterOptions() as $key => $value)
        <option value="{{$key}}" @if($report->filters->isFilteringBy($filter->getFilterField(), $key)) selected @endif>{{$value}}</option>
    @endforeach
</select>