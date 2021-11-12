<div class="m-4">
@foreach($availableFilters as $filter)
    {{ $filter->getTitle() }}
    <select name="{{$filter->field}}" multiple>
        <option value="">--</option>
        @foreach($filter->filterOptions() as $key => $value)
            <option value="{{$key}}">{{$value}}</option>
        @endforeach
    </select>
@endforeach
</div>