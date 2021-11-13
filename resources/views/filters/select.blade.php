<select name="{{$filter->getSelectField()}}[]" multiple>
    <option value="">--</option>
    @foreach($filter->filterOptions() as $key => $value)
        <option value="{{$key}}" @if(in_array($key, request($filter->getSelectField()) ?? [])) selected @endif>{{$value}}</option>
    @endforeach
</select>