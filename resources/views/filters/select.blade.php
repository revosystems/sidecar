<select name="{{$filter->getFilterField()}}[]" multiple>
    <option value="">--</option>
    @foreach($filter->filterOptions() as $key => $value)
        <option value="{{$key}}" @if(in_array($key, request($filter->getFilterField()) ?? [])) selected @endif>{{$value}}</option>
    @endforeach
</select>